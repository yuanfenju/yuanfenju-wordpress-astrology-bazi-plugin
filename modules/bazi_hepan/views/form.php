<div class="yfj-form-container" data-module="<?php echo esc_attr($module_id); ?>">
    <div style="text-align: center; margin-bottom: 25px;">
        <h3 style="margin: 0 0 10px 0; color: var(--yfj-text-dark);"><?php echo $this->t('八字合盘'); ?></h3>
        <p style="font-size: 13px; color: var(--yfj-text-body); margin: 0;">
            <?php echo $this->t('输入甲乙双方出生信息，系统将自动进行精准的合盘运算。'); ?>
        </p>
    </div>

    <form class="yfj-ajax-form">
        <?php wp_nonce_field('yfj_nonce', 'yfj_nonce_field'); ?>

        <!-- 男方信息区块 -->
        <div style="margin-bottom: 20px; padding: 15px; background: #f8fafc; border: 1px solid var(--yfj-border); border-radius: 6px;">
            <h4 style="margin-top: 0; margin-bottom: 15px; color: #334155; font-size: 15px; border-bottom: 1px dashed #cbd5e1; padding-bottom: 8px;"><?php echo $this->t('甲方信息'); ?></h4>

            <div style="display: flex; gap: 15px; margin-bottom: 15px; flex-wrap: wrap;">
                <div class="form-group" style="flex: 1; min-width: 150px; margin-bottom: 0;">
                    <label><?php echo $this->t('甲方姓名：'); ?></label>
                    <input type="text" name="male_name" required placeholder="<?php echo $this->t('请输入甲方姓名'); ?>"
                           value="<?php echo $this->t('求测者'); ?>">
                </div>
                <div class="form-group" style="flex: 1; min-width: 150px; margin-bottom: 0;">
                    <label><?php echo $this->t('日期类型：'); ?></label>
                    <div style="padding-top: 10px;">
                        <label style="display:inline; margin-right: 15px; font-weight: normal; cursor: pointer;">
                            <input type="radio" name="male_type" value="1" checked> <?php echo $this->t('公历 (阳历)'); ?>
                        </label>
                        <label style="display:inline; font-weight: normal; cursor: pointer;">
                            <input type="radio" name="male_type" value="0"> <?php echo $this->t('农历 (阴历)'); ?>
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label><?php echo $this->t('出生日期：'); ?></label>
                <div style="display: flex; gap: 10px;">
                    <select name="male_year" style="flex: 1.2;">
                        <?php
                        $current_year = date('Y');
                        for($y=1930; $y<=($current_year + 5); $y++):
                            ?>
                            <option value="<?php echo $y; ?>" <?php selected($y, 1990); ?>><?php echo $y; ?> <?php echo $this->t('年'); ?></option>
                        <?php endfor; ?>
                    </select>
                    <select name="male_month" style="flex: 1;">
                        <?php for($m=1; $m<=12; $m++): ?>
                            <option value="<?php echo $m; ?>"><?php echo $m; ?> <?php echo $this->t('月'); ?></option>
                        <?php endfor; ?>
                    </select>
                    <select name="male_day" style="flex: 1;">
                        <?php for($d=1; $d<=31; $d++): ?>
                            <option value="<?php echo $d; ?>"><?php echo $d; ?> <?php echo $this->t('日'); ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label><?php echo $this->t('出生时辰：'); ?></label>
                <div style="display: flex; gap: 10px;">
                    <select name="male_hours" style="flex: 1;">
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
                    <select name="male_minute" style="flex: 1;">
                        <option value="0"><?php echo $this->t('未知分'); ?></option>
                        <?php for($min=0; $min<60; $min++): ?>
                            <option value="<?php echo $min; ?>"><?php echo sprintf("%02d", $min); ?> <?php echo $this->t('分'); ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>
        </div>

        <!-- 乙方信息区块 -->
        <div style="margin-bottom: 25px; padding: 15px; background: #eef2ff; border: 1px solid #c7d2fe; border-radius: 6px;">
            <h4 style="margin-top: 0; margin-bottom: 15px; color: #4338ca; font-size: 15px; border-bottom: 1px dashed #c7d2fe; padding-bottom: 8px;"><?php echo $this->t('乙方信息'); ?></h4>

            <div style="display: flex; gap: 15px; margin-bottom: 15px; flex-wrap: wrap;">
                <div class="form-group" style="flex: 1; min-width: 150px; margin-bottom: 0;">
                    <label><?php echo $this->t('乙方姓名：'); ?></label>
                    <input type="text" name="female_name" required placeholder="<?php echo $this->t('请输入乙方姓名'); ?>"
                           value="<?php echo $this->t('求测者'); ?>">
                </div>
                <div class="form-group" style="flex: 1; min-width: 150px; margin-bottom: 0;">
                    <label><?php echo $this->t('日期类型：'); ?></label>
                    <div style="padding-top: 10px;">
                        <label style="display:inline; margin-right: 15px; font-weight: normal; cursor: pointer;">
                            <input type="radio" name="female_type" value="1" checked> <?php echo $this->t('公历 (阳历)'); ?>
                        </label>
                        <label style="display:inline; font-weight: normal; cursor: pointer;">
                            <input type="radio" name="female_type" value="0"> <?php echo $this->t('农历 (阴历)'); ?>
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label><?php echo $this->t('出生日期：'); ?></label>
                <div style="display: flex; gap: 10px;">
                    <select name="female_year" style="flex: 1.2;">
                        <?php
                        for($y=1930; $y<=($current_year + 5); $y++):
                            ?>
                            <option value="<?php echo $y; ?>" <?php selected($y, 1995); ?>><?php echo $y; ?> <?php echo $this->t('年'); ?></option>
                        <?php endfor; ?>
                    </select>
                    <select name="female_month" style="flex: 1;">
                        <?php for($m=1; $m<=12; $m++): ?>
                            <option value="<?php echo $m; ?>"><?php echo $m; ?> <?php echo $this->t('月'); ?></option>
                        <?php endfor; ?>
                    </select>
                    <select name="female_day" style="flex: 1;">
                        <?php for($d=1; $d<=31; $d++): ?>
                            <option value="<?php echo $d; ?>"><?php echo $d; ?> <?php echo $this->t('日'); ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label><?php echo $this->t('出生时辰：'); ?></label>
                <div style="display: flex; gap: 10px;">
                    <select name="female_hours" style="flex: 1;">
                        <?php
                        for($h=0; $h<24; $h++):
                            $zhi_index = floor((($h + 1) % 24) / 2);
                            $zhi = $zhi_arr[$zhi_index];
                            ?>
                            <option value="<?php echo $h; ?>" <?php selected($h, 12); ?>>
                                <?php echo sprintf("%02d", $h); ?>:00 - <?php echo $this->t($zhi . '时'); ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                    <select name="female_minute" style="flex: 1;">
                        <option value="0"><?php echo $this->t('未知分'); ?></option>
                        <?php for($min=0; $min<60; $min++): ?>
                            <option value="<?php echo $min; ?>"><?php echo sprintf("%02d", $min); ?> <?php echo $this->t('分'); ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>
        </div>

        <button type="submit" style="width: 100%;"><?php echo $this->t('立即合盘'); ?></button>
    </form>

    <div class="yfj-loading" style="display:none; text-align: center; margin-top: 15px;"><?php echo $this->t('正在计算中...'); ?></div>
    <div class="yfj-result-area"></div>
</div>