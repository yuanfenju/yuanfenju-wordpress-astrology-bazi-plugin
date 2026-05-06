<style>
    /* 塔罗表单神秘风定制 */
    .yfj-form-container[data-module="<?php echo esc_attr($module_id); ?>"] .yfj-ajax-form {
        display: block !important;
    }
    .yfj-form-container[data-module="<?php echo esc_attr($module_id); ?>"] {
        background: #1e1b4b; /* 深紫星空底色 */
        border-radius: 12px;
        padding: 20px;
        color: #e2e8f0;
    }
    .yfj-tarot-title { color: #fbbf24; text-align: center; margin: 0 0 10px 0; font-family: serif; font-size: 22px; letter-spacing: 2px; }
    .yfj-tarot-desc { color: #94a3b8; text-align: center; font-size: 13px; margin-bottom: 25px; }

    /* 主题选择网格 */
    .yfj-topic-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(100px, 1fr)); gap: 12px; margin-bottom: 20px; }
    .yfj-topic-label { display: block; cursor: pointer; margin: 0; }
    .yfj-topic-label input[type="radio"] { display: none; }
    .yfj-topic-card { background: #312e81; border: 1px solid #4338ca; border-radius: 8px; padding: 15px 5px; text-align: center; transition: all 0.3s; color: #cbd5e1; }
    .yfj-topic-card:hover { border-color: #6366f1; background: #3730a3; }
    .yfj-topic-label input[type="radio"]:checked + .yfj-topic-card { background: #4338ca; border-color: #fbbf24; color: #fbbf24; box-shadow: 0 0 10px rgba(251, 191, 36, 0.3); }
    .yfj-topic-icon { font-size: 24px; margin-bottom: 8px; }

    /* 牌阵选择器 */
    .yfj-spread-select { width: 100%; background: #312e81; color: #f8fafc; border: 1px solid #4338ca; padding: 12px; border-radius: 6px; font-size: 15px; outline: none; appearance: none; cursor: pointer; }
    .yfj-spread-select:focus { border-color: #fbbf24; }

    /* 提交按钮 */
    .yfj-tarot-btn { width: 100%; background: linear-gradient(135deg, #b45309, #d97706); color: #fff; border: none; padding: 14px; border-radius: 6px; font-size: 16px; font-weight: bold; letter-spacing: 1px; cursor: pointer; transition: opacity 0.2s; margin-top: 20px; }
    .yfj-tarot-btn:hover { opacity: 0.9; }
</style>

<div class="yfj-form-container" data-module="<?php echo esc_attr($module_id); ?>">

    <h3 class="yfj-tarot-title"><?php echo $this->t('塔罗星象占卜'); ?></h3>
    <p class="yfj-tarot-desc"><?php echo $this->t('闭上双眼，在心中默念你的困惑，然后开启你的命运之牌。'); ?></p>

    <form class="yfj-ajax-form" id="yfj-tarot-form">
        <?php wp_nonce_field('yfj_nonce', 'yfj_nonce_field'); ?>

        <div style="margin-bottom: 10px; font-size: 14px; color: #fbbf24;"><?php echo $this->t('第一步：选择占卜主题'); ?></div>
        <div class="yfj-topic-grid">
            <?php
            $topics = [
                ['id' => 1, 'name' => '恋爱婚姻', 'icon' => '💖'],
                ['id' => 2, 'name' => '工作学业', 'icon' => '📚'],
                ['id' => 3, 'name' => '人际财富', 'icon' => '💰'],
                ['id' => 4, 'name' => '健康生活', 'icon' => '🌿'],
                ['id' => 5, 'name' => '其它综合', 'icon' => '🔮']
            ];
            foreach ($topics as $index => $t): ?>
                <label class="yfj-topic-label">
                    <input type="radio" name="topic_id" value="<?php echo $t['id']; ?>" <?php checked($index, 0); ?>>
                    <div class="yfj-topic-card">
                        <div class="yfj-topic-icon"><?php echo $t['icon']; ?></div>
                        <div style="font-size: 13px; font-weight: bold;"><?php echo $this->t($t['name']); ?></div>
                    </div>
                </label>
            <?php endforeach; ?>
        </div>

        <div style="margin-bottom: 10px; font-size: 14px; color: #fbbf24; margin-top: 25px;"><?php echo $this->t('第二步：选择塔罗牌阵'); ?></div>
        <select name="spread_id" class="yfj-spread-select">
            <option value="1"><?php echo $this->t('一张牌占卜法 (1张)'); ?></option>
            <option value="2"><?php echo $this->t('二选一牌阵 (2张)'); ?></option>
            <option value="3" selected><?php echo $this->t('圣三角牌阵 (3张 - 推荐)'); ?></option>
            <option value="4"><?php echo $this->t('时光箭牌阵 (3张)'); ?></option>
            <option value="5"><?php echo $this->t('四元素牌阵 (4张)'); ?></option>
            <option value="6"><?php echo $this->t('恋人金字塔 (4张)'); ?></option>
            <option value="7"><?php echo $this->t('五行牌阵 (5张)'); ?></option>
            <option value="8"><?php echo $this->t('恋人牌阵 (5张)'); ?></option>
            <option value="9"><?php echo $this->t('大十字牌阵 (5张)'); ?></option>
            <option value="10"><?php echo $this->t('六芒星牌阵 (6张)'); ?></option>
            <option value="11"><?php echo $this->t('复合牌阵 (6张)'); ?></option>
            <option value="12"><?php echo $this->t('七行星牌阵 (7张)'); ?></option>
            <option value="13"><?php echo $this->t('九宫格牌阵 (9张)'); ?></option>
            <option value="14"><?php echo $this->t('恋人关系深度牌阵 (9张)'); ?></option>
            <option value="15"><?php echo $this->t('凯尔特十字牌阵 (10张)'); ?></option>
            <option value="16"><?php echo $this->t('生命之树牌阵 (11张)'); ?></option>
            <option value="17"><?php echo $this->t('年运周期牌阵 (12张)'); ?></option>
        </select>

        <button type="submit" class="yfj-tarot-btn"><?php echo $this->t('洗牌并抽取卡牌'); ?></button>
    </form>

    <!-- 增加一个洗牌过渡动画区 -->
    <div class="yfj-loading" style="display:none; text-align: center; margin-top: 30px;">
        <div style="font-size: 40px; animation: spin 2s linear infinite;">🌌</div>
        <div style="color: #fbbf24; margin-top: 15px; font-family: serif; letter-spacing: 2px;">
            <?php echo $this->t('正在联结宇宙能量，洗牌中...'); ?>
        </div>
    </div>
    <style>@keyframes spin { 100% { transform: rotate(360deg); } }</style>

    <div class="yfj-result-area"></div>
</div>