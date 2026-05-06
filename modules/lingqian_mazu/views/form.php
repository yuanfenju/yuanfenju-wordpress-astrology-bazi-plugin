<div class="yfj-form-container" data-module="<?php echo esc_attr($module_id); ?>">
    <?php
    // 获取当前语言 (简/繁)，用于加载对应的图片
    $current_lang = get_option('yfj_language', 'zh-cn');
    $lang_suffix = ($current_lang === 'zh-tw') ? 'zh-tw' : 'zh-cn';

    // 妈祖灵签专属图片路径
    $image_url = YFJ_PLUGIN_URL . 'assets/image/lingqian/' . $lang_suffix . '/mazu.jpg';
    ?>

    <!-- 妈祖祈福卡片 UI -->
    <div id="yfj-qiuqian-ui" style="text-align: center; padding: 30px 20px 40px 20px; background: linear-gradient(to bottom, #cffafe, #a5f3fc); border-radius: 12px; margin-bottom: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border: 2px solid #fff;">

        <!-- 妈祖原画展示 -->
        <div style="margin-bottom: 20px; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.15); display: inline-block;">
            <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($this->t('妈祖灵签')); ?>" style="width: 100%; max-width: 400px; height: auto; display: block;">
        </div>

        <h3 style="margin: 0 0 15px 0; color: #0891b2; font-size: 26px; font-weight: bold; letter-spacing: 2px;">
            <?php echo $this->t('妈祖灵签'); ?>
        </h3>

        <p style="font-size: 15px; color: #334155; margin: 0 0 30px 0; line-height: 1.8; font-weight: 500;">
            <?php echo $this->t('海神护佑，平风息浪。祈求天后圣母保佑平安顺遂。'); ?>
        </p>

        <!-- 提交表单 -->
        <form class="yfj-ajax-form" onsubmit="yfjStartShake()">
            <input type="hidden" name="action" value="yfj_lingqian_submit">
            <!-- 传给后端的类型为 mazu -->
            <input type="hidden" name="lingqian_type" value="lingqian_mazu">
            <?php wp_nonce_field('yfj_nonce', 'yfj_nonce_field'); ?>

            <button type="submit" style="background: #0891b2; color: #fff; border: 2px solid rgba(255,255,255,0.5); padding: 16px 55px; font-size: 18px; border-radius: 50px; cursor: pointer; font-weight: bold; box-shadow: 0 4px 15px rgba(8, 145, 178, 0.3); transition: all 0.2s ease;">
                <?php echo $this->t('诚心求签'); ?>
            </button>
        </form>
    </div>

    <!-- 摇晃签筒动画 -->
    <div class="yfj-loading" style="display:none; text-align:center; padding: 40px 20px; background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0;">
        <div class="yfj-shake-tube" style="position: relative; width: 60px; height: 80px; margin: 0 auto 20px auto;">
            <div style="position: absolute; bottom: 0; left: 0; width: 60px; height: 70px; background: #b45309; border-radius: 5px 5px 15px 15px; border: 2px solid #78350f; z-index: 2;"></div>
            <div style="position: absolute; top: -15px; left: 15px; width: 8px; height: 50px; background: #fde68a; border: 1px solid #d97706; border-radius: 2px; transform: rotate(-15deg); z-index: 1;"></div>
            <div style="position: absolute; top: -20px; left: 35px; width: 8px; height: 60px; background: #fde68a; border: 1px solid #d97706; border-radius: 2px; transform: rotate(10deg); z-index: 1;"></div>
            <div style="position: absolute; top: -10px; left: 25px; width: 8px; height: 45px; background: #fde68a; border: 1px solid #d97706; border-radius: 2px; z-index: 1;"></div>
            <div style="position: absolute; bottom: 15px; left: 0; width: 100%; text-align: center; color: #fef3c7; font-weight: bold; font-size: 20px; z-index: 3;"><?php echo $this->t('签'); ?></div>
        </div>
        <div style="color: #0891b2; font-weight: bold; font-size: 16px; letter-spacing: 1px;">
            <?php echo $this->t('正在为您求取妈祖灵签...'); ?>
        </div>
    </div>

    <div class="yfj-result-area"></div>
</div>

<style>
    @keyframes yfj-shake {
        0% { transform: rotate(0deg) translateY(0); }
        25% { transform: rotate(-15deg) translateY(-5px); }
        50% { transform: rotate(15deg) translateY(0); }
        75% { transform: rotate(-15deg) translateY(-5px); }
        100% { transform: rotate(0deg) translateY(0); }
    }
    .yfj-shake-tube { animation: yfj-shake 0.4s infinite ease-in-out; transform-origin: bottom center; }
    .yfj-ajax-form button:hover { transform: scale(1.05); opacity: 0.9; }
</style>

<script>
    function yfjStartShake() { jQuery('#yfj-qiuqian-ui').hide(); }
</script>