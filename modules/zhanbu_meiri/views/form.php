<div class="yfj-form-container" data-module="<?php echo esc_attr($module_id); ?>">

    <!-- 抽签仪式感 UI -->
    <div id="yfj-qiuqian-ui" style="text-align: center; padding: 40px 20px; background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); border-radius: 12px; margin-bottom: 25px; border: 1px solid #fde68a; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">

        <!-- 静态签筒图标 (纯CSS绘制) -->
        <div style="position: relative; width: 60px; height: 80px; margin: 0 auto 20px auto;">
            <div style="position: absolute; bottom: 0; left: 0; width: 60px; height: 70px; background: #b45309; border-radius: 5px 5px 15px 15px; border: 2px solid #78350f; z-index: 2;"></div>
            <div style="position: absolute; top: -15px; left: 15px; width: 8px; height: 50px; background: #fde68a; border: 1px solid #d97706; border-radius: 2px; transform: rotate(-15deg); z-index: 1;"></div>
            <div style="position: absolute; top: -20px; left: 35px; width: 8px; height: 60px; background: #fde68a; border: 1px solid #d97706; border-radius: 2px; transform: rotate(10deg); z-index: 1;"></div>
            <div style="position: absolute; top: -10px; left: 25px; width: 8px; height: 45px; background: #fde68a; border: 1px solid #d97706; border-radius: 2px; z-index: 1;"></div>
            <div style="position: absolute; bottom: 15px; left: 0; width: 100%; text-align: center; color: #fef3c7; font-weight: bold; font-size: 20px; z-index: 3;">吉</div>
        </div>

        <h3 style="margin: 0 0 15px 0; color: #92400e; font-size: 24px; font-weight: bold; letter-spacing: 2px;">
            <?php echo $this->t('每日一占 · 诚心祈福'); ?>
        </h3>

        <p style="font-size: 15px; color: #78350f; margin: 0 0 30px 0; line-height: 1.8;">
            <?php echo $this->t('一事一测，心诚则灵。<br>请在心中默念您的所求之事，然后点击下方按钮抽签。'); ?>
        </p>

        <!-- 极简表单，只包含隐藏的安全校验字段和提交按钮 -->
        <form class="yfj-ajax-form" onsubmit="yfjStartShake()">
            <input type="hidden" name="action" value="yfj_meiri_submit">
            <?php wp_nonce_field('yfj_nonce', 'yfj_nonce_field'); ?>

            <button type="submit" style="background: #dc2626; color: #fff; border: none; padding: 15px 50px; font-size: 18px; border-radius: 50px; cursor: pointer; font-weight: bold; box-shadow: 0 4px 10px rgba(220, 38, 38, 0.3); transition: transform 0.2s ease;">
                <?php echo $this->t('摇卦抽签'); ?>
            </button>
        </form>
    </div>

    <!-- 动态摇晃的签筒 (原本的 yfj-loading 被我改成了这个，AJAX请求时会自动显示) -->
    <div class="yfj-loading" style="display:none; text-align:center; padding: 40px 20px; background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0;">
        <!-- 摇晃动画 -->
        <div class="yfj-shake-tube" style="position: relative; width: 60px; height: 80px; margin: 0 auto 20px auto;">
            <div style="position: absolute; bottom: 0; left: 0; width: 60px; height: 70px; background: #b45309; border-radius: 5px 5px 15px 15px; border: 2px solid #78350f; z-index: 2;"></div>
            <div style="position: absolute; top: -15px; left: 15px; width: 8px; height: 50px; background: #fde68a; border: 1px solid #d97706; border-radius: 2px; transform: rotate(-15deg); z-index: 1;"></div>
            <div style="position: absolute; top: -20px; left: 35px; width: 8px; height: 60px; background: #fde68a; border: 1px solid #d97706; border-radius: 2px; transform: rotate(10deg); z-index: 1;"></div>
            <div style="position: absolute; top: -10px; left: 25px; width: 8px; height: 45px; background: #fde68a; border: 1px solid #d97706; border-radius: 2px; z-index: 1;"></div>
            <div style="position: absolute; bottom: 15px; left: 0; width: 100%; text-align: center; color: #fef3c7; font-weight: bold; font-size: 20px; z-index: 3;">摇</div>
        </div>
        <div style="color: #dc2626; font-weight: bold; font-size: 16px; letter-spacing: 1px;">
            <?php echo $this->t('天机流转，正在为您诚心摇卦...'); ?>
        </div>
    </div>

    <div class="yfj-result-area"></div>
</div>

<style>
    /* CSS 摇卦动画 */
    @keyframes yfj-shake {
        0% { transform: rotate(0deg) translateY(0); }
        25% { transform: rotate(-15deg) translateY(-5px); }
        50% { transform: rotate(15deg) translateY(0); }
        75% { transform: rotate(-15deg) translateY(-5px); }
        100% { transform: rotate(0deg) translateY(0); }
    }
    .yfj-shake-tube {
        animation: yfj-shake 0.4s infinite ease-in-out;
        transform-origin: bottom center;
    }
</style>

<script>
    function yfjStartShake() {
        // 隐藏静态的输入区，让后面的 yfj-loading 动画展示出来
        jQuery('#yfj-qiuqian-ui').hide();
    }
</script>