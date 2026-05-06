<div class="yfj-form-container" data-module="<?php echo esc_attr($module_id); ?>">
    <div style="text-align: center; margin-bottom: 25px;">
        <h3 style="margin: 0 0 10px 0; color: var(--yfj-text-dark);"><?php echo $this->t('紫微斗数流盘'); ?></h3>
        <p style="font-size: 13px; color: var(--yfj-text-body); margin: 0;">
            <?php echo $this->t('输入命主出生信息，系统将自动进行精准的运算。'); ?>
        </p>
    </div>

    <form class="yfj-ajax-form">
        <?php wp_nonce_field('yfj_nonce', 'yfj_nonce_field'); ?>

        <!-- 【核心】：紫微流盘初次请求，必须固定为 0 (先天+大限盘) -->
        <input type="hidden" name="pan_model" value="0">

        <div style="display: flex; gap: 15px; margin-bottom: 15px; flex-wrap: wrap;">
            <div class="form-group" style="flex: 1; min-width: 150px; margin-bottom: 0;">
                <label><?php echo $this->t('命主姓名：'); ?></label>
                <input type="text" name="name" required placeholder="<?php echo $this->t('请输入姓名'); ?>" value="<?php echo $this->t('求测者'); ?>">
            </div>
            <div class="form-group" style="flex: 1; min-width: 150px; margin-bottom: 0;">
                <label><?php echo $this->t('性别：'); ?></label>
                <div style="padding-top: 10px;">
                    <label style="display:inline; margin-right: 15px; font-weight: normal; cursor: pointer;">
                        <input type="radio" name="sex" value="0" checked> <?php echo $this->t('男性'); ?>
                    </label>
                    <label style="display:inline; font-weight: normal; cursor: pointer;">
                        <input type="radio" name="sex" value="1"> <?php echo $this->t('女性'); ?>
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 15px;">
            <label><?php echo $this->t('日期类型：'); ?></label>
            <div style="padding-top: 5px;">
                <label style="display:inline; margin-right: 15px; font-weight: normal; cursor: pointer;">
                    <input type="radio" name="type" value="1" checked> <?php echo $this->t('公历 (阳历)'); ?>
                </label>
                <label style="display:inline; font-weight: normal; cursor: pointer;">
                    <input type="radio" name="type" value="0"> <?php echo $this->t('农历 (阴历)'); ?>
                </label>
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 15px;">
            <label><?php echo $this->t('出生日期：'); ?></label>
            <div style="display: flex; gap: 10px;">
                <select name="year" id="yfj_year" style="flex: 1.2;">
                    <?php
                    $current_year = date('Y');
                    for($y=1930; $y<=($current_year + 5); $y++):
                        ?>
                        <option value="<?php echo $y; ?>" <?php selected($y, 1990); ?>><?php echo $y; ?> <?php echo $this->t('年'); ?></option>
                    <?php endfor; ?>
                </select>
                <select name="month" style="flex: 1;">
                    <?php for($m=1; $m<=12; $m++): ?>
                        <option value="<?php echo $m; ?>"><?php echo $m; ?> <?php echo $this->t('月'); ?></option>
                    <?php endfor; ?>
                </select>
                <select name="day" style="flex: 1;">
                    <?php for($d=1; $d<=31; $d++): ?>
                        <option value="<?php echo $d; ?>"><?php echo $d; ?> <?php echo $this->t('日'); ?></option>
                    <?php endfor; ?>
                </select>
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 15px;">
            <label><?php echo $this->t('出生时辰：'); ?></label>
            <div style="display: flex; gap: 10px;">
                <select name="hours" style="flex: 1;">
                    <?php
                    $zhi_arr = ['子', '丑', '寅', '卯', '辰', '巳', '午', '未', '申', '酉', '戌', '亥'];
                    for($h=0; $h<24; $h++):
                        $zhi_index = floor((($h + 1) % 24) / 2);
                        $zhi = $zhi_arr[$zhi_index];
                        ?>
                        <option value="<?php echo $h; ?>" <?php selected($h, 12); ?>>
                            <?php echo sprintf("%02d", $h); ?>:00 - <?php echo $this->t($zhi . '时'); ?>
                        </option>
                    <?php endfor; ?>
                </select>
                <select name="minute" style="flex: 1;">
                    <option value="0"><?php echo $this->t('未知分'); ?></option>
                    <?php for($min=0; $min<60; $min++): ?>
                        <option value="<?php echo $min; ?>"><?php echo sprintf("%02d", $min); ?> <?php echo $this->t('分'); ?></option>
                    <?php endfor; ?>
                </select>
            </div>
        </div>

        <!-- 紫微斗数专业排盘设置区 -->
        <div style="background: #f8fafc; padding: 15px; border: 1px solid var(--yfj-border); border-radius: 6px; margin-bottom: 20px;">
            <h4 style="margin: 0 0 15px 0; font-size: 14px; color: #334155; border-bottom: 1px dashed #cbd5e1; padding-bottom: 8px;">
                <span class="dashicons dashicons-admin-generic" style="font-size: 16px; vertical-align: middle;"></span>
                <?php echo $this->t('紫微流盘专业设置'); ?>
            </h4>

            <div class="form-group" style="margin-bottom: 15px;">
                <label style="color: #475569; font-size: 13px;"><?php echo $this->t('长生顺逆：'); ?></label>
                <select name="pros_cons" style="font-size: 13px; padding: 4px 8px;">
                    <option value="2" selected><?php echo $this->t('按年干阴阳 (推荐)'); ?></option>
                    <option value="1"><?php echo $this->t('全部顺排'); ?></option>
                    <option value="3"><?php echo $this->t('按照男女'); ?></option>
                </select>
            </div>

            <div style="display: flex; gap: 15px;">
                <div class="form-group" style="flex: 1; margin-bottom: 0;">
                    <label style="color: #475569; font-size: 13px;"><?php echo $this->t('大限顺逆：'); ?></label>
                    <select name="dx_model" style="font-size: 13px; padding: 4px 8px;">
                        <option value="1" selected><?php echo $this->t('阳男阴女顺,阴男阳女逆 (推荐)'); ?></option>
                        <option value="2"><?php echo $this->t('男顺女逆'); ?></option>
                    </select>
                </div>
                <div class="form-group" style="flex: 1; margin-bottom: 0;">
                    <label style="color: #475569; font-size: 13px;"><?php echo $this->t('闰月分界：'); ?></label>
                    <select name="leap_bound" style="font-size: 13px; padding: 4px 8px;">
                        <option value="0" selected><?php echo $this->t('月中分界 (推荐)'); ?></option>
                        <option value="1"><?php echo $this->t('作下月计算'); ?></option>
                    </select>
                </div>
            </div>
        </div>

        <button type="submit"><?php echo $this->t('立即排盘'); ?></button>
    </form>

    <div class="yfj-loading" style="display:none;"><?php echo $this->t('正在计算中...'); ?></div>
    <div class="yfj-result-area"></div>
</div>