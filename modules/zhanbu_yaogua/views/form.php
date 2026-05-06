<style>
    /* 摇卦占卜表单 - 古风定制 */
    .yfj-form-container[data-module="<?php echo esc_attr($module_id); ?>"] {
        background: #fdfbf7; /* 米黄宣纸底色 */
        border: 1px solid #e5d9c5;
        border-radius: 12px;
        padding: 40px 20px;
        color: #2c2c2c;
        background-image: radial-gradient(#e5d9c5 1px, transparent 1px);
        background-size: 20px 20px;
        box-shadow: inset 0 0 40px rgba(139, 0, 0, 0.03);
    }

    .yfj-yaogua-title {
        text-align: center;
        color: #8b0000; /* 朱红 */
        font-family: "KaiTi", "STKaiti", serif;
        font-size: 28px;
        font-weight: bold;
        letter-spacing: 4px;
        margin: 0 0 15px 0;
    }

    .yfj-yaogua-desc {
        text-align: center;
        color: #5c4b37;
        font-size: 14px;
        line-height: 1.8;
        max-width: 400px;
        margin: 0 auto 30px auto;
    }

    .yfj-btn-yaogua {
        display: block;
        width: 100%;
        max-width: 280px;
        margin: 0 auto;
        background: linear-gradient(135deg, #8b0000, #a52a2a);
        color: #fdfbf7;
        border: 2px solid #5c1a1a;
        padding: 16px;
        border-radius: 8px;
        font-size: 18px;
        font-family: "KaiTi", "STKaiti", serif;
        font-weight: bold;
        letter-spacing: 2px;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(139, 0, 0, 0.3);
    }
    .yfj-btn-yaogua:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(139, 0, 0, 0.4); }

    /* 铜钱摇动动画区 */
    .yfj-yaogua-loading { text-align: center; padding: 30px 0; }
    .yfj-yaogua-loading img { width: 120px; margin-bottom: 20px; }
    .yfj-yaogua-loading p { color: #8b0000; font-family: "KaiTi", serif; font-size: 18px; letter-spacing: 2px; animation: yfjPulse 1.5s infinite; }
    @keyframes yfjPulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.6; } }
</style>

<div class="yfj-form-container" data-module="<?php echo esc_attr($module_id); ?>">

    <div class="yfj-yaogua-intro">
        <h3 class="yfj-yaogua-title"><?php echo $this->t('周易金钱课占卜'); ?></h3>
        <p class="yfj-yaogua-desc">
            <?php echo $this->t('古法掷钱，六爻成卦。'); ?><br>
            <?php echo $this->t('请静心屏气，在心中默念您的困惑，诚心点击下方按钮起卦。心诚则灵。'); ?>
        </p>
    </div>

    <!-- 因为没有参数，直接放一个空表单用于触发 AJAX -->
    <form class="yfj-ajax-form">
        <?php wp_nonce_field('yfj_nonce', 'yfj_nonce_field'); ?>
        <button type="submit" class="yfj-btn-yaogua"><?php echo $this->t('🙏 诚心起卦'); ?></button>
    </form>

    <!-- 深度定制的纯 CSS 3D 摇卦加载动画 -->
    <div class="yfj-loading yfj-yaogua-loading" style="display:none;">
        <style>
            /* 三枚铜钱的容器 */
            .yfj-coins-toss {
                display: flex;
                justify-content: center;
                gap: 25px;
                margin: 20px 0 40px 0;
                perspective: 800px; /* 3D 景深 */
            }

            /* 单枚铜钱的纯 CSS 绘制 */
            .yfj-css-coin {
                width: 60px;
                height: 60px;
                background: linear-gradient(135deg, #d4af37, #f3e5ab, #aa801e, #d4af37);
                border-radius: 50%;
                border: 3px solid #8b5a2b;
                box-shadow:
                        0 10px 15px rgba(139, 0, 0, 0.2),
                        inset 0 0 10px rgba(139, 90, 43, 0.8);
                position: relative;
                transform-style: preserve-3d;
                /* 核心摇卦动画：翻滚 + 上下跃动 */
                animation: yfjToss 0.8s cubic-bezier(0.4, 0, 0.2, 1) infinite;
            }

            /* 铜钱中间的方孔（天圆地方） */
            .yfj-css-coin::before {
                content: "";
                position: absolute;
                top: 50%; left: 50%;
                transform: translate(-50%, -50%);
                width: 18px; height: 18px;
                /* 这里的颜色与表单背景色一致，制造出镂空的错觉 */
                background: #fdfbf7;
                border: 2px solid #8b5a2b;
                box-shadow: inset 0 0 4px rgba(0,0,0,0.5);
            }

            /* 铜钱内圈的刻痕装饰 */
            .yfj-css-coin::after {
                content: "";
                position: absolute;
                top: 5px; left: 5px; right: 5px; bottom: 5px;
                border: 1px dashed rgba(139, 90, 43, 0.4);
                border-radius: 50%;
                pointer-events: none;
            }

            /* 定义 3D 翻转和跃动的关键帧 */
            @keyframes yfjToss {
                0%   { transform: translateY(0) rotateX(0deg); box-shadow: 0 10px 15px rgba(139, 0, 0, 0.2); }
                50%  { transform: translateY(-50px) rotateX(540deg); box-shadow: 0 40px 20px rgba(139, 0, 0, 0.1); }
                100% { transform: translateY(0) rotateX(1080deg); box-shadow: 0 10px 15px rgba(139, 0, 0, 0.2); }
            }

            /* 让三枚铜钱产生错落有致的起伏感（延迟动画） */
            .yfj-css-coin:nth-child(1) { animation-delay: 0s; }
            .yfj-css-coin:nth-child(2) { animation-delay: 0.15s; }
            .yfj-css-coin:nth-child(3) { animation-delay: 0.07s; }
        </style>

        <!-- 动画结构 -->
        <div class="yfj-coins-toss">
            <div class="yfj-css-coin"></div>
            <div class="yfj-css-coin"></div>
            <div class="yfj-css-coin"></div>
        </div>

        <p><?php echo $this->t('三枚金钱半空舞，正在为您请卦...'); ?></p>
    </div>

    <div class="yfj-result-area"></div>
</div>