<?php
// 安全拦截
if (empty($data) || !is_array($data) || empty($data['cards'])) {
    echo '<div style="color:#ef4444; text-align:center; padding: 20px;">' . $this->t('获取塔罗牌阵失败，请重新抽取。') . '</div>';
    return;
}
?>

<?php if(isset($is_demo) && $is_demo): ?>
    <div style="background:#fff3cd; padding:12px 15px; color:#856404; margin-bottom:20px; border-radius:6px; border-left: 4px solid #ffeeba; font-size: 14px; line-height: 1.6;">
        <span class="dashicons dashicons-info" style="vertical-align: middle;"></span>
        <strong><?php echo $this->t('Sandbox 演示模式说明：'); ?></strong><br>
        <?php echo $this->t('当前为沙盒测试环境。下方展示的排盘与解析均为系统预设的固定模拟数据，与您填写的测算信息完全无关，仅供预览界面排版效果。'); ?>
    </div>
<?php endif; ?>

<!-- 隐式预加载本次占卜抽到的塔罗牌，确保翻牌动画 0 延迟、无白屏闪烁 -->
<div style="display: none;" aria-hidden="true">
    <!-- 预加载牌背 -->
    <link rel="preload" as="image" href="<?php echo YFJ_PLUGIN_URL; ?>assets/image/taluo/back.jpg">
    <?php foreach($data['cards'] as $card):
        // 动态构建本地图片路径：插件URL + assets/image/taluo/ + 卡牌编号 + .jpg
        $local_img_url = YFJ_PLUGIN_URL . 'assets/image/taluo/' . intval($card['card_no']) . '.jpg';
        ?>
        <link rel="preload" as="image" href="<?php echo esc_url($local_img_url); ?>">
        <img src="<?php echo esc_url($local_img_url); ?>" alt="preload">
    <?php endforeach; ?>
</div>

<style>
    /* ================= 3D 翻牌动画核心 CSS ================= */
    :root {
        /* 使用 PHP 动态输出插件的绝对根 URL，完美引用本地牌背资源 */
        --tarot-back-img: url('<?php echo YFJ_PLUGIN_URL; ?>assets/image/taluo/back.jpg');
    }

    .yfj-tarot-wrapper { font-family: serif; color: #e2e8f0; }

    /* 桌面布景 */
    .yfj-tarot-table { background: #0f172a; border: 1px solid #312e81; border-radius: 12px; padding: 25px 15px; margin-top: 20px; box-shadow: inset 0 0 30px rgba(0,0,0,0.5); }
    .yfj-tarot-instruction { text-align: center; color: #fbbf24; font-size: 16px; margin-bottom: 25px; letter-spacing: 1px; animation: pulse 2s infinite; }
    @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.5; } }

    /* 卡牌网格排布 (自适应宽度排列) */
    .yfj-cards-container { display: flex; flex-wrap: wrap; justify-content: center; gap: 20px; perspective: 1000px; }

    .yfj-card-slot { width: 120px; display: flex; flex-direction: column; align-items: center; }
    .yfj-card-position-name { font-size: 12px; color: #94a3b8; margin-bottom: 8px; text-align: center; }

    /* 3D 翻转容器 */
    .yfj-flip-card { background-color: transparent; width: 100%; height: 200px; cursor: pointer; }
    .yfj-flip-card-inner { position: relative; width: 100%; height: 100%; text-align: center; transition: transform 0.8s cubic-bezier(0.4, 0.2, 0.2, 1); transform-style: preserve-3d; }

    /* 翻开状态的类 */
    .yfj-flip-card.is-flipped .yfj-flip-card-inner { transform: rotateY(180deg); cursor: default; }

    /* 【核心黑科技】：如果是逆位牌，先在 Y 轴翻转展示图片，再在 Z 轴倒转 180 度！ */
    .yfj-flip-card.is-flipped.is-reversed .yfj-flip-card-inner { transform: rotateY(180deg) rotateZ(180deg); }

    .yfj-flip-front, .yfj-flip-back { position: absolute; width: 100%; height: 100%; -webkit-backface-visibility: hidden; backface-visibility: hidden; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.5); border: 2px solid #4338ca; }

    /* 正面(牌背图案) */
    .yfj-flip-front { background-image: var(--tarot-back-img); background-size: cover; background-position: center; background-color: #1e1b4b; }

    /* 背面(实际牌面图案) */
    .yfj-flip-back { background-size: cover; background-position: center; transform: rotateY(180deg); border-color: #fbbf24; }

    /* 解读区域 (默认隐藏) */
    .yfj-interpretation-area { display: none; margin-top: 30px; animation: slideUp 0.8s ease forwards; }
    @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

    /* 文本排版样式 */
    .yfj-tarot-panel { background: #1e1b4b; border: 1px solid #312e81; border-radius: 8px; padding: 20px; margin-bottom: 20px; }
    .yfj-tarot-h4 { color: #fbbf24; border-bottom: 1px dashed #4338ca; padding-bottom: 10px; margin-top: 0; margin-bottom: 15px; font-size: 18px; }
    .yfj-tarot-p { font-size: 14px; color: #cbd5e1; line-height: 1.8; margin-bottom: 10px; }
    .yfj-tarot-label { color: #818cf8; font-weight: bold; }
    .yfj-tarot-highlight { color: #fbbf24; font-weight: bold; }
</style>

<div class="yfj-result-wrapper yfj-tarot-wrapper">

    <!-- 测算环境信息 -->
    <div style="text-align: center; color: #64748b; font-size: 12px; margin-bottom: 10px;">
        <!-- 动态时局信息包裹翻译 -->
        <?php echo $this->t('时局能量：'); ?> <?php echo esc_html($this->t($data['environment']['time_ganzhi'])); ?>
        (<?php echo esc_html($this->t($data['environment']['time_element'])); ?>)
    </div>

    <!-- 牌桌与翻牌区 -->
    <div class="yfj-tarot-table">
        <div class="yfj-tarot-instruction" id="yfj-tarot-instruction">
            <?php echo $this->t('请依次点击下方的卡牌，揭开你的命运指引'); ?>
        </div>

        <div class="yfj-cards-container">
            <?php foreach($data['cards'] as $index => $card):
                $is_reversed = ($card['orientation_code'] == 0) ? 'is-reversed' : '';
                $local_img_url = YFJ_PLUGIN_URL . 'assets/image/taluo/' . intval($card['card_no']) . '.jpg';
                ?>
                <div class="yfj-card-slot">
                    <!-- 动态位置名称包裹翻译 -->
                    <div class="yfj-card-position-name"><?php echo esc_html($this->t($card['positions_name'])); ?></div>
                    <div class="yfj-flip-card" data-reversed="<?php echo $is_reversed; ?>">
                        <div class="yfj-flip-card-inner">
                            <div class="yfj-flip-front"></div>
                            <div class="yfj-flip-back" style="background-image: url('<?php echo esc_url($local_img_url); ?>');"></div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- 详细解读区 -->
    <div class="yfj-interpretation-area" id="yfj-interpretation-area">

        <!-- 综合解读 -->
        <div class="yfj-tarot-panel">
            <h4 class="yfj-tarot-h4">✨ <?php echo $this->t('综合命运神谕'); ?></h4>
            <!-- 长文本全面包裹翻译 -->
            <div class="yfj-tarot-p"><span class="yfj-tarot-label"><?php echo $this->t('牌阵总览：'); ?></span> <?php echo esc_html($this->t($data['overall_interpretation']['summary_message'])); ?></div>
            <div class="yfj-tarot-p" style="margin-top: 15px; padding: 15px; background: rgba(0,0,0,0.2); border-left: 3px solid #fbbf24;">
                <span class="yfj-tarot-label" style="color: #fbbf24;"><?php echo $this->t('塔罗神谕：'); ?></span> <?php echo esc_html($this->t($data['overall_interpretation']['oracle_message'])); ?>
            </div>
        </div>

        <!-- 逐张牌解析 -->
        <h4 style="color: #cbd5e1; text-align: center; margin: 30px 0 20px; font-family: serif;"><?php echo $this->t('— 卡牌详细启示 —'); ?></h4>

        <?php foreach($data['cards'] as $card): ?>
            <div class="yfj-tarot-panel">
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px dashed #4338ca; padding-bottom: 10px; margin-bottom: 15px;">
                    <div style="font-size: 16px; font-weight: bold; color: #e2e8f0;">
                        <!-- 牌位、牌名、正逆位包裹翻译 -->
                        <?php echo esc_html($this->t($card['positions_name'])); ?>：
                        <span style="color: #fbbf24;"><?php echo esc_html($this->t($card['card_name'])); ?></span>
                        <span style="font-size: 12px; color: <?php echo ($card['orientation_code']==1) ? '#34d399' : '#f87171'; ?>;">
                            (<?php echo esc_html($this->t($card['orientation_text'])); ?>)
                        </span>
                    </div>
                    <div style="font-size: 12px; color: #818cf8;">
                        <!-- 星座、元素包裹翻译 -->
                        <?php echo esc_html($this->t($card['card_astrology'])); ?> · <?php echo esc_html($this->t($card['card_element'])); ?><?php echo $this->t('元素'); ?>
                    </div>
                </div>

                <!-- 描述文本包裹翻译 -->
                <div class="yfj-tarot-p"><span class="yfj-tarot-label"><?php echo $this->t('牌面意象：'); ?></span> <?php echo esc_html($this->t($card['card_description'])); ?></div>
                <div class="yfj-tarot-p"><span class="yfj-tarot-label"><?php echo $this->t('核心关键：'); ?></span> <?php echo esc_html($this->t($card['card_keywords'])); ?></div>

                <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid rgba(67, 56, 202, 0.5);">
                    <!-- 三大解析维度包裹翻译 -->
                    <div class="yfj-tarot-p"><span class="yfj-tarot-highlight"><?php echo $this->t('【基础释义】'); ?></span><br><?php echo esc_html($this->t($card['card_interpretation']['general'])); ?></div>
                    <div class="yfj-tarot-p"><span class="yfj-tarot-highlight"><?php echo $this->t('【主题剖析】'); ?></span><br><?php echo esc_html($this->t($card['card_interpretation']['topic'])); ?></div>
                    <div class="yfj-tarot-p"><span class="yfj-tarot-highlight" style="color: #34d399;"><?php echo $this->t('【命运建议】'); ?></span><br><?php echo esc_html($this->t($card['card_interpretation']['advice'])); ?></div>
                </div>
            </div>
        <?php endforeach; ?>

        <!-- 测算告诫，免责声明 -->
        <?php echo $this->get_disclaimer_html(); ?>

        <!-- 返回按钮 -->
        <div style="text-align: center; margin-top: 30px;">
            <button onclick="jQuery('.yfj-result-area').hide(); jQuery('.yfj-ajax-form').fadeIn();"
                    style="background: #312e81; color: #cbd5e1; border: 1px solid #4338ca; padding: 12px 30px; border-radius: 6px; font-weight: bold; cursor: pointer; transition: 0.3s;">
                <?php echo $this->t('结束占卜，重置牌阵'); ?>
            </button>
        </div>
    </div>
</div>

<script>
    jQuery(document).ready(function($) {
        var totalCards = $('.yfj-flip-card').length;
        var flippedCards = 0;
        var isRevealed = false;

        // 绑定翻牌点击事件
        $('.yfj-flip-card').on('click', function() {
            var $this = $(this);

            if ($this.hasClass('is-flipped')) return;

            if ($this.attr('data-reversed') === 'is-reversed') {
                $this.addClass('is-reversed');
            }

            $this.addClass('is-flipped');
            flippedCards++;

            // 当所有牌都被翻开时，展示详细解读
            if (flippedCards === totalCards && !isRevealed) {
                isRevealed = true;
                // 【重点修正】：JS 里的提示文本也套用 PHP 的翻译方法渲染
                $('#yfj-tarot-instruction').text('<?php echo esc_js($this->t('命运之牌已全部揭晓，请查阅下方神谕')); ?>');

                setTimeout(function() {
                    $('#yfj-interpretation-area').css('display', 'block');
                    $('html, body').animate({
                        scrollTop: $("#yfj-interpretation-area").offset().top - 50
                    }, 1000);
                }, 800);
            }
        });
    });
</script>