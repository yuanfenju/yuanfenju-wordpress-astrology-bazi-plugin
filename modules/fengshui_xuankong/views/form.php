<div class="yfj-form-container" data-module="<?php echo esc_attr($module_id); ?>">
    <div style="text-align: center; margin-bottom: 25px;">
        <h3 style="margin: 0 0 10px 0; color: var(--yfj-text-dark);"><?php echo $this->t('玄空飞星风水排盘'); ?></h3>
        <p style="font-size: 13px; color: var(--yfj-text-body); margin: 0;">
            <?php echo $this->t('输入宅主信息与风水朝向，系统将自动进行三元九运精准排盘。'); ?>
        </p>
    </div>

    <form class="yfj-ajax-form">
        <input type="hidden" name="action" value="yfj_xuankong_submit">
        <?php wp_nonce_field('yfj_nonce', 'yfj_nonce_field'); ?>

        <div style="display: flex; gap: 15px; margin-bottom: 15px; flex-wrap: wrap;">
            <div class="form-group" style="flex: 1; min-width: 150px; margin-bottom: 0;">
                <label><?php echo $this->t('宅主性别：'); ?></label>
                <div style="padding-top: 10px;">
                    <label style="display:inline; margin-right: 15px; font-weight: normal; cursor: pointer;">
                        <input type="radio" name="sex" value="0" checked> <?php echo $this->t('男性'); ?>
                    </label>
                    <label style="display:inline; font-weight: normal; cursor: pointer;">
                        <input type="radio" name="sex" value="1"> <?php echo $this->t('女性'); ?>
                    </label>
                </div>
            </div>
            <div class="form-group" style="flex: 1; min-width: 150px; margin-bottom: 0;">
                <label><?php echo $this->t('宅主出生年农历 (阴历)：'); ?></label>
                <select name="born_year" style="width: 100%; box-sizing: border-box; padding: 10px; border-radius: 6px; border: 1px solid var(--yfj-border);">
                    <?php
                    $born_default = 1988;
                    for($i = 1910; $i <= 2100; $i++) {
                        echo '<option value="'.$i.'"'.($i==$born_default ? ' selected' : '').'>'.$i.' '.$this->t('年').'</option>';
                    }
                    ?>
                </select>
            </div>
        </div>

        <div style="display: flex; gap: 15px; margin-bottom: 15px; flex-wrap: wrap;">
            <div class="form-group" style="flex: 1; min-width: 150px; margin-bottom: 0;">
                <label><?php echo $this->t('元运：'); ?></label>
                <select name="yun_model" style="width: 100%; box-sizing: border-box; padding: 10px; border-radius: 6px; border: 1px solid var(--yfj-border);">
                    <?php
                    $yun_arr = ['一运','二运','三运','四运','五运','六运','七运','八运','九运'];
                    foreach($yun_arr as $k => $v) {
                        // 默认八运(7) 或 九运(8)，按目前年份通常选八运或九运
                        echo '<option value="'.$k.'"'.($k==8 ? ' selected' : '').'>'.$this->t($v).'</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="form-group" style="flex: 1; min-width: 150px; margin-bottom: 0;">
                <label><?php echo $this->t('山向：'); ?></label>
                <select name="shan_model" style="width: 100%; box-sizing: border-box; padding: 10px; border-radius: 6px; border: 1px solid var(--yfj-border);">
                    <?php
                    $shan_arr = [
                        '壬山丙向','子山午向','癸山丁向','丑山未向','艮山坤向','寅山申向','甲山庚向','卯山酉向',
                        '乙山辛向','辰山戌向','巽山乾向','巳山亥向','丙山壬向','午山子向','丁山癸向','未山丑向',
                        '坤山艮向','申山寅向','庚山甲向','酉山卯向','辛山乙向','戌山辰向','乾山巽向','亥山巳向'
                    ];
                    foreach($shan_arr as $k => $v) {
                        echo '<option value="'.$k.'">'.$this->t($v).'</option>';
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 15px;">
            <label><?php echo $this->t('兼向(是否用替)：'); ?></label>
            <div style="padding-top: 5px;">
                <label style="display:inline; margin-right: 15px; font-weight: normal; cursor: pointer;">
                    <input type="radio" name="ti_model" value="0" checked> <?php echo $this->t('否'); ?>
                </label>
                <label style="display:inline; font-weight: normal; cursor: pointer;">
                    <input type="radio" name="ti_model" value="1"> <?php echo $this->t('是'); ?>
                </label>
            </div>
        </div>

        <!-- 排龙诀区域 -->
        <div class="form-group" style="margin-bottom: 15px; background: #f8fafc; padding: 15px; border: 1px solid #e2e8f0; border-radius: 6px;">
            <label style="margin-bottom: 10px; display: block;"><?php echo $this->t('排龙诀：'); ?></label>
            <div style="padding-top: 5px;">
                <label style="display:inline; margin-right: 15px; font-weight: normal; cursor: pointer;">
                    <input type="radio" name="long_model" value="0" checked onchange="document.getElementById('yfj-shuikou-area').style.display='none';"> <?php echo $this->t('否'); ?>
                </label>
                <label style="display:inline; font-weight: normal; cursor: pointer;">
                    <input type="radio" name="long_model" value="1" onchange="document.getElementById('yfj-shuikou-area').style.display='block';"> <?php echo $this->t('是'); ?>
                </label>
            </div>

            <div id="yfj-shuikou-area" style="display: none; padding-top: 15px; border-top: 1px dashed #cbd5e1; margin-top: 15px;">
                <label style="display: block; margin-bottom: 5px;"><?php echo $this->t('水口方位：'); ?></label>
                <select name="long_shui_kou" style="width: 100%; box-sizing: border-box; padding: 10px; border-radius: 6px; border: 1px solid #cbd5e1;">
                    <?php
                    $shuikou_arr = ['壬','子','癸','丑','艮','寅','甲','卯','乙','辰','巽','巳','丙','午','丁','未','坤','申','庚','酉','辛','戌','乾','亥'];
                    foreach($shuikou_arr as $k => $v) {
                        echo '<option value="'.$k.'">'.$this->t($v).'</option>';
                    }
                    ?>
                </select>
            </div>
        </div>

        <!-- 命盘流年流月区域 -->
        <div class="form-group" style="margin-bottom: 25px; background: #f8fafc; padding: 15px; border: 1px solid #e2e8f0; border-radius: 6px;">
            <label style="margin-bottom: 10px; display: block;"><?php echo $this->t('流年命盘：'); ?></label>
            <div style="padding-top: 5px;">
                <label style="display:inline; margin-right: 15px; font-weight: normal; cursor: pointer;">
                    <input type="radio" name="ming_model" value="0" checked onchange="document.getElementById('yfj-mingpan-area').style.display='none';"> <?php echo $this->t('否'); ?>
                </label>
                <label style="display:inline; font-weight: normal; cursor: pointer;">
                    <input type="radio" name="ming_model" value="1" onchange="document.getElementById('yfj-mingpan-area').style.display='flex';"> <?php echo $this->t('是'); ?>
                </label>
            </div>

            <div id="yfj-mingpan-area" style="display: none; gap: 10px; padding-top: 15px; border-top: 1px dashed #cbd5e1; margin-top: 15px;">
                <div style="flex: 1;">
                    <label style="display: block; margin-bottom: 5px; font-size: 13px;"><?php echo $this->t('农历流年'); ?></label>
                    <select name="ming_liu_year" style="width: 100%; box-sizing: border-box; padding: 10px; border-radius: 6px; border: 1px solid #cbd5e1;">
                        <?php
                        $current_year = date('Y');
                        for($y=1930; $y<=($current_year + 5); $y++):
                            ?>
                            <option value="<?php echo $y; ?>" <?php selected($y, $current_year); ?>><?php echo $y; ?> <?php echo $this->t('年'); ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div style="flex: 1;">
                    <label style="display: block; margin-bottom: 5px; font-size: 13px;"><?php echo $this->t('农历流月'); ?></label>
                    <select name="ming_liu_month" style="width: 100%; box-sizing: border-box; padding: 10px; border-radius: 6px; border: 1px solid #cbd5e1;">
                        <?php for($m=1; $m<=12; $m++): ?>
                            <option value="<?php echo $m; ?>" <?php selected($m, date('n')); ?>><?php echo $m; ?> <?php echo $this->t('月'); ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>
        </div>

        <button type="submit"><?php echo $this->t('立即排盘'); ?></button>
    </form>

    <div class="yfj-loading" style="display:none;"><?php echo $this->t('正在精算三元九运...'); ?></div>
    <div class="yfj-result-area"></div>
</div>