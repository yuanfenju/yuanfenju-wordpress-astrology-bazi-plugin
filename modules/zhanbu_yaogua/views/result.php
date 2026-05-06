<?php
// 安全拦截
if (empty($data) || !is_array($data)) {
    echo '<div style="color:#8b0000; text-align:center; padding: 20px;">' . $this->t('起卦失败，请稍后重试。') . '</div>';
    return;
}

$gua_id = intval($data['id']);

// 【核心黑科技】：周易六十四卦二进制映射表 (1=阳爻, 0=阴爻)
// 顺序严格按照《周易》文王六十四卦卦序，每组字符串从左到右代表从上到下（第6爻到初爻）
$gua_map = [
    1=>'111111', 2=>'000000', 3=>'010001', 4=>'100010', 5=>'010111', 6=>'111010', 7=>'000010', 8=>'010000',
    9=>'110111', 10=>'111011', 11=>'000111', 12=>'111000', 13=>'101111', 14=>'111101', 15=>'000100', 16=>'001000',
    17=>'011001', 18=>'100110', 19=>'000011', 20=>'110000', 21=>'101001', 22=>'100101', 23=>'100000', 24=>'000001',
    25=>'111001', 26=>'100111', 27=>'100001', 28=>'011110', 29=>'010010', 30=>'101101', 31=>'011100', 32=>'001110',
    33=>'111100', 34=>'001111', 35=>'101000', 36=>'000101', 37=>'110101', 38=>'101011', 39=>'010100', 40=>'001010',
    41=>'100011', 42=>'110001', 43=>'011111', 44=>'111110', 45=>'011000', 46=>'000110', 47=>'011010', 48=>'010110',
    49=>'011101', 50=>'101110', 51=>'001001', 52=>'100100', 53=>'110100', 54=>'001011', 55=>'001101', 56=>'101100',
    57=>'110110', 58=>'011011', 59=>'110010', 60=>'010011', 61=>'110011', 62=>'001100', 63=>'010101', 64=>'101010'
];

// 获取当前卦的二进制结构，如果越界则默认显示乾卦
$gua_binary = isset($gua_map[$gua_id]) ? $gua_map[$gua_id] : '111111';
?>

<?php if(isset($is_demo) && $is_demo): ?>
    <div style="background:#fffbeb; padding:12px 15px; color:#b45309; margin-bottom:20px; border-radius:6px; border-left: 4px solid #fcd34d; font-size: 14px;">
        <span class="dashicons dashicons-info" style="vertical-align: middle;"></span>
        <strong><?php echo $this->t('Sandbox 演示模式说明：'); ?></strong><br>
        <?php echo $this->t('当前为沙盒测试环境。下方展示的排盘与解析均为系统预设的固定模拟数据，与您填写的测算信息完全无关，仅供预览界面排版效果。'); ?>
    </div>
<?php endif; ?>

<style>
    .yfj-yg-wrapper { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "KaiTi", serif; color: #2c2c2c; }

    /* 古风面板容器 */
    .yfj-yg-panel { background: #fdfbf7; border: 1px solid #e5d9c5; border-radius: 8px; margin-bottom: 24px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); overflow: hidden; position: relative; }
    .yfj-yg-panel::before { content: ""; position: absolute; top: 0; left: 0; right: 0; bottom: 0; background-image: radial-gradient(#e5d9c5 1px, transparent 1px); background-size: 20px 20px; opacity: 0.5; pointer-events: none; }

    .yfj-yg-panel-heading { background: rgba(229, 217, 197, 0.3); padding: 15px 20px; border-bottom: 1px solid #e5d9c5; font-family: "KaiTi", serif; font-size: 18px; font-weight: bold; color: #8b0000; display: flex; align-items: center; gap: 8px; position: relative; z-index: 1; }
    .yfj-yg-panel-body { padding: 25px; font-size: 15px; line-height: 1.8; position: relative; z-index: 1; }

    /* 顶部本卦信息区 */
    .yfj-yg-hero { display: flex; flex-wrap: wrap; gap: 20px; align-items: flex-start; margin-bottom: 20px; }

    /* ======== 纯 CSS 矢量卦象绘图区 ======== */
    .yfj-yg-image-box { flex: 0 0 100px; text-align: center; }
    .yfj-css-hexagram {
        width: 70px;
        height: 70px;
        margin: 0 auto 10px auto;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 8px;
        background: #fdfbf7;
        border: 2px solid #5c4b37;
        border-radius: 4px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .yfj-yao-row {
        height: 6px;
        display: flex;
        justify-content: space-between;
    }
    .yfj-yao-yang { width: 100%; height: 100%; background-color: #8b0000; border-radius: 1px; }
    .yfj-yao-yin { width: 44%; height: 100%; background-color: #8b0000; border-radius: 1px; }
    /* ======================================= */

    .yfj-yg-image-box .yfj-yg-id { font-size: 13px; color: #8b0000; border: 1px solid #8b0000; border-radius: 20px; padding: 2px 10px; display: inline-block; font-weight: bold; }

    .yfj-yg-core { flex: 1; min-width: 200px; }
    .yfj-yg-name { font-size: 24px; font-family: "KaiTi", serif; font-weight: bold; color: #8b0000; margin-bottom: 10px; border-bottom: 1px dashed #e5d9c5; padding-bottom: 10px; }
    .yfj-yg-desc-item { margin-bottom: 12px; }
    .yfj-yg-label { font-weight: bold; color: #5c4b37; background: #f5eedf; padding: 2px 6px; border-radius: 4px; margin-right: 8px; font-size: 13px; }

    /* 解卦六大维度网格 */
    .yfj-yg-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 15px; margin-top: 10px; }
    .yfj-yg-card { background: rgba(255, 255, 255, 0.6); border: 1px solid #e5d9c5; border-radius: 6px; overflow: hidden; display: flex; flex-direction: column; }
    .yfj-yg-card-header { background: rgba(229, 217, 197, 0.4); padding: 10px 15px; font-weight: bold; color: #5c4b37; border-bottom: 1px dashed #e5d9c5; font-family: "KaiTi", serif; font-size: 16px; display: flex; align-items: center; gap: 6px; }
    .yfj-yg-card-body { padding: 15px; font-size: 14px; color: #333; flex: 1; }

    /* 底部按钮 */
    .yfj-btn-retoss { display: inline-block; background: #fdfbf7; color: #8b0000; border: 1px solid #8b0000; padding: 10px 30px; border-radius: 6px; font-size: 16px; font-family: "KaiTi", serif; font-weight: bold; cursor: pointer; transition: 0.3s; text-decoration: none; }
    .yfj-btn-retoss:hover { background: #8b0000; color: #fff; }
</style>

<div class="yfj-yg-wrapper">

    <!-- 1. 卦象基本信息 -->
    <div class="yfj-yg-panel">
        <div class="yfj-yg-panel-heading">
            <span class="dashicons dashicons-book-alt"></span> <?php echo $this->t('本卦卦象解析'); ?>
        </div>
        <div class="yfj-yg-panel-body">
            <div class="yfj-yg-hero">

                <!-- 纯代码生成的矢量卦象图 -->
                <div class="yfj-yg-image-box">
                    <div class="yfj-css-hexagram">
                        <?php
                        // 遍历6位二进制字符串，生成 6 根爻线
                        for($i = 0; $i < 6; $i++):
                            ?>
                            <div class="yfj-yao-row">
                                <?php if($gua_binary[$i] === '1'): ?>
                                    <div class="yfj-yao-yang"></div>
                                <?php else: ?>
                                    <div class="yfj-yao-yin"></div>
                                    <div class="yfj-yao-yin"></div>
                                <?php endif; ?>
                            </div>
                        <?php endfor; ?>
                    </div>
                    <div class="yfj-yg-id"><?php echo $this->t('第'); ?> <?php echo $gua_id; ?> <?php echo $this->t('卦'); ?></div>
                </div>

                <!-- 卦名与核心释义 -->
                <div class="yfj-yg-core">
                    <div class="yfj-yg-name"><?php echo esc_html($this->t($data['common_desc1'] ?? '')); ?></div>
                    <div class="yfj-yg-desc-item">
                        <span class="yfj-yg-label"><?php echo $this->t('象曰'); ?></span>
                        <?php echo esc_html($this->t($data['common_desc2'] ?? '')); ?>
                    </div>
                    <div class="yfj-yg-desc-item">
                        <span class="yfj-yg-label"><?php echo $this->t('解卦'); ?></span>
                        <span style="color: #8b0000;"><?php echo esc_html($this->t($data['common_desc3'] ?? '')); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. 详细解卦信息 -->
    <div class="yfj-yg-panel">
        <div class="yfj-yg-panel-heading">
            <span class="dashicons dashicons-search"></span> <?php echo $this->t('所问之事断语'); ?>
        </div>
        <div class="yfj-yg-panel-body" style="padding-top: 10px;">
            <div class="yfj-yg-grid">

                <div class="yfj-yg-card">
                    <div class="yfj-yg-card-header"><span class="dashicons dashicons-portfolio"></span> <?php echo $this->t('事业谋望'); ?></div>
                    <div class="yfj-yg-card-body"><?php echo esc_html($this->t($data['shiye'] ?? '暂无数据')); ?></div>
                </div>

                <div class="yfj-yg-card">
                    <div class="yfj-yg-card-header"><span class="dashicons dashicons-money-alt"></span> <?php echo $this->t('经商财运'); ?></div>
                    <div class="yfj-yg-card-body"><?php echo esc_html($this->t($data['jingshang'] ?? '暂无数据')); ?></div>
                </div>

                <div class="yfj-yg-card">
                    <div class="yfj-yg-card-header"><span class="dashicons dashicons-welcome-learn-more"></span> <?php echo $this->t('求名学业'); ?></div>
                    <div class="yfj-yg-card-body"><?php echo esc_html($this->t($data['qiuming'] ?? '暂无数据')); ?></div>
                </div>

                <div class="yfj-yg-card">
                    <div class="yfj-yg-card-header"><span class="dashicons dashicons-location"></span> <?php echo $this->t('外出动向'); ?></div>
                    <div class="yfj-yg-card-body"><?php echo esc_html($this->t($data['waichu'] ?? '暂无数据')); ?></div>
                </div>

                <div class="yfj-yg-card">
                    <div class="yfj-yg-card-header"><span class="dashicons dashicons-heart"></span> <?php echo $this->t('婚恋姻缘'); ?></div>
                    <div class="yfj-yg-card-body"><?php echo esc_html($this->t($data['hunlian'] ?? '暂无数据')); ?></div>
                </div>

                <div class="yfj-yg-card">
                    <div class="yfj-yg-card-header"><span class="dashicons dashicons-lightbulb"></span> <?php echo $this->t('谋事决策'); ?></div>
                    <div class="yfj-yg-card-body"><?php echo esc_html($this->t($data['juece'] ?? '暂无数据')); ?></div>
                </div>

            </div>
        </div>
    </div>

    <!-- 测算告诫，免责声明 -->
    <?php echo $this->get_disclaimer_html(); ?>

    <!-- 返回重测 -->
    <div style="text-align: center; margin-top: 15px;">
        <button onclick="jQuery('.yfj-result-area').hide(); jQuery('.yfj-ajax-form').fadeIn(); jQuery('.yfj-yaogua-intro').fadeIn();" class="yfj-btn-retoss">
            <?php echo $this->t('返回重新摇卦'); ?>
        </button>
    </div>

</div>

<script>
    // 在展示结果时，确保表单前导语隐藏，使得界面更整洁
    jQuery(document).ready(function($){
        $('.yfj-yaogua-intro').hide();
    });
</script>