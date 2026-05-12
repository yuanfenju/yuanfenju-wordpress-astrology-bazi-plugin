<div class="yfj-form-container" data-module="<?php echo esc_attr($module_id); ?>">
    <div style="text-align: center; margin-bottom: 25px;">
        <h3 style="margin: 0 0 10px 0; color: var(--yfj-text-dark);"><?php echo $this->t('西方占星组合盘'); ?></h3>
        <p style="font-size: 13px; color: var(--yfj-text-body); margin: 0;">
            <?php echo $this->t('输入双方的出生信息，系统将精准绘制并解析两人的生命缘分与能量交互。'); ?>
        </p>
    </div>

    <form class="yfj-ajax-form">
        <?php wp_nonce_field('yfj_nonce', 'yfj_nonce_field'); ?>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 20px; margin-bottom: 25px;">

            <div style="background: #f8fafc; padding: 20px; border-radius: 8px; border: 1px solid #e2e8f0;">
                <h4 style="margin-top: 0; color: #2563eb; border-bottom: 1px dashed #bfdbfe; padding-bottom: 10px; margin-bottom: 15px;">
                    <span class="dashicons dashicons-admin-users"></span> <?php echo $this->t('A方信息'); ?>
                </h4>

                <div style="display: flex; gap: 15px; margin-bottom: 15px; flex-wrap: wrap;">
                    <div class="form-group" style="flex: 1; min-width: 150px; margin-bottom: 0;">
                        <label><?php echo $this->t('A方姓名：'); ?></label>
                        <input type="text" name="person_a_name" required placeholder="<?php echo $this->t('请输入A方姓名'); ?>" value="<?php echo $this->t('求测者A'); ?>">
                    </div>
                    <div class="form-group" style="flex: 1; min-width: 150px; margin-bottom: 0;">
                        <label><?php echo $this->t('性别：'); ?></label>
                        <div style="padding-top: 10px;">
                            <label style="display:inline; margin-right: 15px; font-weight: normal; cursor: pointer;">
                                <input type="radio" name="person_a_sex" value="0" checked> <?php echo $this->t('男'); ?>
                            </label>
                            <label style="display:inline; font-weight: normal; cursor: pointer;">
                                <input type="radio" name="person_a_sex" value="1"> <?php echo $this->t('女'); ?>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <label><?php echo $this->t('出生日期 (公历/阳历)：'); ?></label>
                    <div style="display: flex; gap: 10px;">
                        <select name="person_a_year" id="yfj_year_a" style="flex: 1.2;">
                            <?php $current_year = date('Y'); for($y=1930; $y<=($current_year + 5); $y++): ?>
                                <option value="<?php echo $y; ?>" <?php selected($y, 1990); ?>><?php echo $y; ?> <?php echo $this->t('年'); ?></option>
                            <?php endfor; ?>
                        </select>
                        <select name="person_a_month" id="yfj_month_a" style="flex: 1;">
                            <?php for($m=1; $m<=12; $m++): ?>
                                <option value="<?php echo $m; ?>"><?php echo $m; ?> <?php echo $this->t('月'); ?></option>
                            <?php endfor; ?>
                        </select>
                        <select name="person_a_day" id="yfj_day_a" style="flex: 1;">
                            <?php for($d=1; $d<=31; $d++): ?>
                                <option value="<?php echo $d; ?>"><?php echo $d; ?> <?php echo $this->t('日'); ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <label><?php echo $this->t('出生时辰：'); ?></label>
                    <div style="display: flex; gap: 10px;">
                        <select name="person_a_hours" id="yfj_hour_a" style="flex: 1;">
                            <?php for($h=0; $h<24; $h++): ?>
                                <option value="<?php echo $h; ?>" <?php selected($h, 12); ?>><?php echo sprintf("%02d", $h); ?> <?php echo $this->t('时'); ?></option>
                            <?php endfor; ?>
                        </select>
                        <select name="person_a_minute" id="yfj_minute_a" style="flex: 1;">
                            <?php for($min=0; $min<60; $min++): ?>
                                <option value="<?php echo $min; ?>"><?php echo sprintf("%02d", $min); ?> <?php echo $this->t('分'); ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <label><?php echo $this->t('出生时区：'); ?></label>
                    <select name="person_a_timezone" style="width: 100%;">
                        <option value="Asia/Shanghai" selected><?php echo $this->t('中国标准时间'); ?></option>
                        <option value="Asia/Taipei"><?php echo $this->t('台北'); ?></option>
                        <option value="Asia/Hong_Kong"><?php echo $this->t('香港'); ?></option>
                        <option value="Asia/Macau"><?php echo $this->t('澳门'); ?></option>
                        <option value="Asia/Tokyo"><?php echo $this->t('日本'); ?></option>
                        <option value="Asia/Seoul"><?php echo $this->t('韩国'); ?></option>
                        <option value="Asia/Singapore"><?php echo $this->t('新加坡'); ?></option>
                        <option value="America/New_York"><?php echo $this->t('美东'); ?></option>
                        <option value="America/Los_Angeles"><?php echo $this->t('美西'); ?></option>
                        <option value="Europe/London"><?php echo $this->t('英国'); ?></option>
                        <option value="Europe/Paris"><?php echo $this->t('法国'); ?></option>
                        <option value="Australia/Sydney"><?php echo $this->t('悉尼'); ?></option>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 0; background: #fff; padding: 15px; border: 1px solid #e2e8f0; border-radius: 6px;">
                    <label style="margin-bottom: 10px;"><?php echo $this->t('出生地点：'); ?></label>
                    <div style="padding-top: 5px; margin-bottom: 10px;">
                        <label style="display:inline; margin-right: 15px; font-weight: normal; cursor: pointer;">
                            <input type="radio" name="person_a_loc_type" value="china" id="loc_china_a" checked> <?php echo $this->t('中国地区'); ?>
                        </label>
                        <label style="display:inline; font-weight: normal; cursor: pointer;">
                            <input type="radio" name="person_a_loc_type" value="custom" id="loc_custom_a"> <?php echo $this->t('自定义经纬度'); ?>
                        </label>
                    </div>

                    <input type="hidden" name="person_a_longitude" id="raw_longitude_a" value="116.405285">
                    <input type="hidden" name="person_a_latitude" id="raw_latitude_a" value="39.904989">

                    <div id="yfj_city_area_a" style="padding-top: 10px; border-top: 1px dashed #cbd5e1; margin-top: 10px;">
                        <div style="display: flex; gap: 10px;">
                            <select id="yfj_province_a" style="flex: 1;"><option value=""><?php echo $this->t('加载中...'); ?></option></select>
                            <select id="yfj_city_a" style="flex: 1;"><option value=""><?php echo $this->t('请选择市'); ?></option></select>
                            <select id="yfj_district_a" style="flex: 1;"><option value=""><?php echo $this->t('请选择区'); ?></option></select>
                        </div>
                    </div>

                    <div id="yfj_custom_area_a" style="display: none; padding-top: 10px; border-top: 1px dashed #cbd5e1; margin-top: 10px;">
                        <div style="display: flex; gap: 10px; margin-bottom: 10px;">
                            <span style="width: 45px; font-size: 15px; display: flex; align-items: center; color: #293d51;"><?php echo $this->t('经度：'); ?></span>
                            <select id="lng_deg_a" style="flex: 1;">
                                <?php for($i=0; $i<=180; $i++): ?><option value="<?php echo $i; ?>" <?php selected($i, 116); ?>><?php echo $i; ?>°</option><?php endfor; ?>
                            </select>
                            <select id="lng_min_a" style="flex: 1;">
                                <?php for($i=0; $i<=59; $i++): ?><option value="<?php echo $i; ?>" <?php selected($i, 24); ?>><?php echo sprintf("%02d", $i); ?>′</option><?php endfor; ?>
                            </select>
                            <select id="lng_dir_a" style="flex: 1;">
                                <option value="E" selected><?php echo $this->t('东经 (E)'); ?></option>
                                <option value="W"><?php echo $this->t('西经 (W)'); ?></option>
                            </select>
                        </div>
                        <div style="display: flex; gap: 10px;">
                            <span style="width: 45px; font-size: 15px; display: flex; align-items: center; color: #293d51"><?php echo $this->t('纬度：'); ?></span>
                            <select id="lat_deg_a" style="flex: 1;">
                                <?php for($i=0; $i<=90; $i++): ?><option value="<?php echo $i; ?>" <?php selected($i, 39); ?>><?php echo $i; ?>°</option><?php endfor; ?>
                            </select>
                            <select id="lat_min_a" style="flex: 1;">
                                <?php for($i=0; $i<=59; $i++): ?><option value="<?php echo $i; ?>" <?php selected($i, 54); ?>><?php echo sprintf("%02d", $i); ?>′</option><?php endfor; ?>
                            </select>
                            <select id="lat_dir_a" style="flex: 1;">
                                <option value="N" selected><?php echo $this->t('北纬 (N)'); ?></option>
                                <option value="S"><?php echo $this->t('南纬 (S)'); ?></option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div style="background: #fdf2f8; padding: 20px; border-radius: 8px; border: 1px solid #fce7f3;">
                <h4 style="margin-top: 0; color: #db2777; border-bottom: 1px dashed #fbcfe8; padding-bottom: 10px; margin-bottom: 15px;">
                    <span class="dashicons dashicons-admin-users"></span> <?php echo $this->t('B方信息'); ?>
                </h4>

                <div style="display: flex; gap: 15px; margin-bottom: 15px; flex-wrap: wrap;">
                    <div class="form-group" style="flex: 1; min-width: 150px; margin-bottom: 0;">
                        <label><?php echo $this->t('B方姓名：'); ?></label>
                        <input type="text" name="person_b_name" required placeholder="<?php echo $this->t('请输入B方姓名'); ?>" value="<?php echo $this->t('求测者B'); ?>">
                    </div>
                    <div class="form-group" style="flex: 1; min-width: 150px; margin-bottom: 0;">
                        <label><?php echo $this->t('性别：'); ?></label>
                        <div style="padding-top: 10px;">
                            <label style="display:inline; margin-right: 15px; font-weight: normal; cursor: pointer;">
                                <input type="radio" name="person_b_sex" value="0"> <?php echo $this->t('男'); ?>
                            </label>
                            <label style="display:inline; font-weight: normal; cursor: pointer;">
                                <input type="radio" name="person_b_sex" value="1" checked> <?php echo $this->t('女'); ?>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <label><?php echo $this->t('出生日期 (公历/阳历)：'); ?></label>
                    <div style="display: flex; gap: 10px;">
                        <select name="person_b_year" id="yfj_year_b" style="flex: 1.2;">
                            <?php for($y=1930; $y<=($current_year + 5); $y++): ?>
                                <option value="<?php echo $y; ?>" <?php selected($y, 1990); ?>><?php echo $y; ?> <?php echo $this->t('年'); ?></option>
                            <?php endfor; ?>
                        </select>
                        <select name="person_b_month" id="yfj_month_b" style="flex: 1;">
                            <?php for($m=1; $m<=12; $m++): ?>
                                <option value="<?php echo $m; ?>"><?php echo $m; ?> <?php echo $this->t('月'); ?></option>
                            <?php endfor; ?>
                        </select>
                        <select name="person_b_day" id="yfj_day_b" style="flex: 1;">
                            <?php for($d=1; $d<=31; $d++): ?>
                                <option value="<?php echo $d; ?>"><?php echo $d; ?> <?php echo $this->t('日'); ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <label><?php echo $this->t('出生时辰：'); ?></label>
                    <div style="display: flex; gap: 10px;">
                        <select name="person_b_hours" id="yfj_hour_b" style="flex: 1;">
                            <?php for($h=0; $h<24; $h++): ?>
                                <option value="<?php echo $h; ?>" <?php selected($h, 12); ?>><?php echo sprintf("%02d", $h); ?> <?php echo $this->t('时'); ?></option>
                            <?php endfor; ?>
                        </select>
                        <select name="person_b_minute" id="yfj_minute_b" style="flex: 1;">
                            <?php for($min=0; $min<60; $min++): ?>
                                <option value="<?php echo $min; ?>"><?php echo sprintf("%02d", $min); ?> <?php echo $this->t('分'); ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <label><?php echo $this->t('出生时区：'); ?></label>
                    <select name="person_b_timezone" style="width: 100%;">
                        <option value="Asia/Shanghai" selected><?php echo $this->t('中国标准时间'); ?></option>
                        <option value="Asia/Taipei"><?php echo $this->t('台北'); ?></option>
                        <option value="Asia/Hong_Kong"><?php echo $this->t('香港'); ?></option>
                        <option value="Asia/Macau"><?php echo $this->t('澳门'); ?></option>
                        <option value="Asia/Tokyo"><?php echo $this->t('日本'); ?></option>
                        <option value="Asia/Seoul"><?php echo $this->t('韩国'); ?></option>
                        <option value="Asia/Singapore"><?php echo $this->t('新加坡'); ?></option>
                        <option value="America/New_York"><?php echo $this->t('美东'); ?></option>
                        <option value="America/Los_Angeles"><?php echo $this->t('美西'); ?></option>
                        <option value="Europe/London"><?php echo $this->t('英国'); ?></option>
                        <option value="Europe/Paris"><?php echo $this->t('法国'); ?></option>
                        <option value="Australia/Sydney"><?php echo $this->t('悉尼'); ?></option>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 0; background: #fff; padding: 15px; border: 1px solid #fce7f3; border-radius: 6px;">
                    <label style="margin-bottom: 10px;"><?php echo $this->t('出生地点：'); ?></label>
                    <div style="padding-top: 5px; margin-bottom: 10px;">
                        <label style="display:inline; margin-right: 15px; font-weight: normal; cursor: pointer;">
                            <input type="radio" name="person_b_loc_type" value="china" id="loc_china_b" checked> <?php echo $this->t('中国地区'); ?>
                        </label>
                        <label style="display:inline; font-weight: normal; cursor: pointer;">
                            <input type="radio" name="person_b_loc_type" value="custom" id="loc_custom_b"> <?php echo $this->t('自定义经纬度'); ?>
                        </label>
                    </div>

                    <input type="hidden" name="person_b_longitude" id="raw_longitude_b" value="116.405285">
                    <input type="hidden" name="person_b_latitude" id="raw_latitude_b" value="39.904989">

                    <div id="yfj_city_area_b" style="padding-top: 10px; border-top: 1px dashed #fbcfe8; margin-top: 10px;">
                        <div style="display: flex; gap: 10px;">
                            <select id="yfj_province_b" style="flex: 1;"><option value=""><?php echo $this->t('加载中...'); ?></option></select>
                            <select id="yfj_city_b" style="flex: 1;"><option value=""><?php echo $this->t('请选择市'); ?></option></select>
                            <select id="yfj_district_b" style="flex: 1;"><option value=""><?php echo $this->t('请选择区'); ?></option></select>
                        </div>
                    </div>

                    <div id="yfj_custom_area_b" style="display: none; padding-top: 10px; border-top: 1px dashed #fbcfe8; margin-top: 10px;">
                        <div style="display: flex; gap: 10px; margin-bottom: 10px;">
                            <span style="width: 45px; font-size: 15px; display: flex; align-items: center; color: #293d51;"><?php echo $this->t('经度：'); ?></span>
                            <select id="lng_deg_b" style="flex: 1;">
                                <?php for($i=0; $i<=180; $i++): ?><option value="<?php echo $i; ?>" <?php selected($i, 116); ?>><?php echo $i; ?>°</option><?php endfor; ?>
                            </select>
                            <select id="lng_min_b" style="flex: 1;">
                                <?php for($i=0; $i<=59; $i++): ?><option value="<?php echo $i; ?>" <?php selected($i, 24); ?>><?php echo sprintf("%02d", $i); ?>′</option><?php endfor; ?>
                            </select>
                            <select id="lng_dir_b" style="flex: 1;">
                                <option value="E" selected><?php echo $this->t('东经 (E)'); ?></option>
                                <option value="W"><?php echo $this->t('西经 (W)'); ?></option>
                            </select>
                        </div>
                        <div style="display: flex; gap: 10px;">
                            <span style="width: 45px; font-size: 15px; display: flex; align-items: center; color: #293d51"><?php echo $this->t('纬度：'); ?></span>
                            <select id="lat_deg_b" style="flex: 1;">
                                <?php for($i=0; $i<=90; $i++): ?><option value="<?php echo $i; ?>" <?php selected($i, 39); ?>><?php echo $i; ?>°</option><?php endfor; ?>
                            </select>
                            <select id="lat_min_b" style="flex: 1;">
                                <?php for($i=0; $i<=59; $i++): ?><option value="<?php echo $i; ?>" <?php selected($i, 54); ?>><?php echo sprintf("%02d", $i); ?>′</option><?php endfor; ?>
                            </select>
                            <select id="lat_dir_b" style="flex: 1;">
                                <option value="N" selected><?php echo $this->t('北纬 (N)'); ?></option>
                                <option value="S"><?php echo $this->t('南纬 (S)'); ?></option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div style="display: flex; gap: 15px; margin-bottom: 25px;">
            <div class="form-group" style="flex: 1; margin-bottom: 0;">
                <label><?php echo $this->t('宫位制：'); ?></label>
                <select name="house_system">
                    <option value="P" selected>Placidus</option>
                    <option value="K">Koch</option>
                    <option value="O">Porphyry</option>
                    <option value="R">Regiomontanus</option>
                    <option value="C">Campanus</option>
                    <option value="W">Whole Sign</option>
                    <option value="E">Equal</option>
                </select>
            </div>
            <div class="form-group" style="flex: 1; margin-bottom: 0;">
                <label><?php echo $this->t('相位容许度：'); ?></label>
                <select name="orb_model">
                    <option value="2" selected><?php echo $this->t('标准模式 (推荐)'); ?></option>
                    <option value="1"><?php echo $this->t('严格模式 (偏小)'); ?></option>
                    <option value="3"><?php echo $this->t('宽松模式 (偏大)'); ?></option>
                </select>
            </div>
        </div>

        <button type="submit"><?php echo $this->t('立即排盘'); ?></button>
    </form>

    <div class="yfj-loading" style="display:none;"><?php echo $this->t('正在计算星盘轨迹...'); ?></div>
    <div class="yfj-result-area"></div>
</div>

<script>
    (function() {
        // ================= A 方逻辑彻底展开 (适配 PHP 正则引擎) =================
        const locChina_a = document.getElementById('loc_china_a');
        const locCustom_a = document.getElementById('loc_custom_a');
        const cityArea_a = document.getElementById('yfj_city_area_a');
        const customArea_a = document.getElementById('yfj_custom_area_a');
        const provSelect_a = document.getElementById('yfj_province_a');
        const citySelect_a = document.getElementById('yfj_city_a');
        const distSelect_a = document.getElementById('yfj_district_a');

        function updateCoords_a(lat, lng) {
            let rawLat_a = document.getElementById('raw_latitude_a');
            let rawLng_a = document.getElementById('raw_longitude_a');
            if(rawLat_a) rawLat_a.value = lat;
            if(rawLng_a) rawLng_a.value = lng;
        }

        function toggleLoc_a() {
            if(!cityArea_a || !customArea_a) return;
            if (locChina_a.checked) {
                cityArea_a.style.display = 'block';
                customArea_a.style.display = 'none';
                if(distSelect_a) distSelect_a.dispatchEvent(new Event('change'));
            } else {
                cityArea_a.style.display = 'none';
                customArea_a.style.display = 'block';
                calcCustom_a();
            }
        }
        if(locChina_a) locChina_a.addEventListener('change', toggleLoc_a);
        if(locCustom_a) locCustom_a.addEventListener('change', toggleLoc_a);

        function calcCustom_a() {
            let dEl = document.getElementById('lng_deg_a'), mEl = document.getElementById('lng_min_a'), dirEl = document.getElementById('lng_dir_a');
            if(!dEl || !mEl || !dirEl) return;
            let lng = (parseFloat(dEl.value)||0) + (parseFloat(mEl.value)||0)/60;
            if (dirEl.value === 'W') lng = -lng;

            let latDEl = document.getElementById('lat_deg_a'), latMEl = document.getElementById('lat_min_a'), latDirEl = document.getElementById('lat_dir_a');
            if(!latDEl || !latMEl || !latDirEl) return;
            let lat = (parseFloat(latDEl.value)||0) + (parseFloat(latMEl.value)||0)/60;
            if (latDirEl.value === 'S') lat = -lat;

            updateCoords_a(lat.toFixed(6), lng.toFixed(6));
        }

        let l1_a = document.getElementById('lng_deg_a'); if(l1_a) l1_a.addEventListener('change', function(){ if(locCustom_a && locCustom_a.checked) calcCustom_a(); });
        let l2_a = document.getElementById('lng_min_a'); if(l2_a) l2_a.addEventListener('change', function(){ if(locCustom_a && locCustom_a.checked) calcCustom_a(); });
        let l3_a = document.getElementById('lng_dir_a'); if(l3_a) l3_a.addEventListener('change', function(){ if(locCustom_a && locCustom_a.checked) calcCustom_a(); });
        let l4_a = document.getElementById('lat_deg_a'); if(l4_a) l4_a.addEventListener('change', function(){ if(locCustom_a && locCustom_a.checked) calcCustom_a(); });
        let l5_a = document.getElementById('lat_min_a'); if(l5_a) l5_a.addEventListener('change', function(){ if(locCustom_a && locCustom_a.checked) calcCustom_a(); });
        let l6_a = document.getElementById('lat_dir_a'); if(l6_a) l6_a.addEventListener('change', function(){ if(locCustom_a && locCustom_a.checked) calcCustom_a(); });


        // ================= B 方逻辑彻底展开 (适配 PHP 正则引擎) =================
        const locChina_b = document.getElementById('loc_china_b');
        const locCustom_b = document.getElementById('loc_custom_b');
        const cityArea_b = document.getElementById('yfj_city_area_b');
        const customArea_b = document.getElementById('yfj_custom_area_b');
        const provSelect_b = document.getElementById('yfj_province_b');
        const citySelect_b = document.getElementById('yfj_city_b');
        const distSelect_b = document.getElementById('yfj_district_b');

        function updateCoords_b(lat, lng) {
            let rawLat_b = document.getElementById('raw_latitude_b');
            let rawLng_b = document.getElementById('raw_longitude_b');
            if(rawLat_b) rawLat_b.value = lat;
            if(rawLng_b) rawLng_b.value = lng;
        }

        function toggleLoc_b() {
            if(!cityArea_b || !customArea_b) return;
            if (locChina_b.checked) {
                cityArea_b.style.display = 'block';
                customArea_b.style.display = 'none';
                if(distSelect_b) distSelect_b.dispatchEvent(new Event('change'));
            } else {
                cityArea_b.style.display = 'none';
                customArea_b.style.display = 'block';
                calcCustom_b();
            }
        }
        if(locChina_b) locChina_b.addEventListener('change', toggleLoc_b);
        if(locCustom_b) locCustom_b.addEventListener('change', toggleLoc_b);

        function calcCustom_b() {
            let dEl = document.getElementById('lng_deg_b'), mEl = document.getElementById('lng_min_b'), dirEl = document.getElementById('lng_dir_b');
            if(!dEl || !mEl || !dirEl) return;
            let lng = (parseFloat(dEl.value)||0) + (parseFloat(mEl.value)||0)/60;
            if (dirEl.value === 'W') lng = -lng;

            let latDEl = document.getElementById('lat_deg_b'), latMEl = document.getElementById('lat_min_b'), latDirEl = document.getElementById('lat_dir_b');
            if(!latDEl || !latMEl || !latDirEl) return;
            let lat = (parseFloat(latDEl.value)||0) + (parseFloat(latMEl.value)||0)/60;
            if (latDirEl.value === 'S') lat = -lat;

            updateCoords_b(lat.toFixed(6), lng.toFixed(6));
        }

        let l1_b = document.getElementById('lng_deg_b'); if(l1_b) l1_b.addEventListener('change', function(){ if(locCustom_b && locCustom_b.checked) calcCustom_b(); });
        let l2_b = document.getElementById('lng_min_b'); if(l2_b) l2_b.addEventListener('change', function(){ if(locCustom_b && locCustom_b.checked) calcCustom_b(); });
        let l3_b = document.getElementById('lng_dir_b'); if(l3_b) l3_b.addEventListener('change', function(){ if(locCustom_b && locCustom_b.checked) calcCustom_b(); });
        let l4_b = document.getElementById('lat_deg_b'); if(l4_b) l4_b.addEventListener('change', function(){ if(locCustom_b && locCustom_b.checked) calcCustom_b(); });
        let l5_b = document.getElementById('lat_min_b'); if(l5_b) l5_b.addEventListener('change', function(){ if(locCustom_b && locCustom_b.checked) calcCustom_b(); });
        let l6_b = document.getElementById('lat_dir_b'); if(l6_b) l6_b.addEventListener('change', function(){ if(locCustom_b && locCustom_b.checked) calcCustom_b(); });


        // ================= 获取 city.json 并在 A、B 两端分别渲染 =================
        let hierarchicalData = {};
        <?php
        $current_lang = get_option('yfj_language', 'zh-cn');
        $city_file_name = ($current_lang === 'zh-tw') ? 'city_tw.json' : 'city.json';
        ?>
        const jsonUrl = '<?php echo YFJ_PLUGIN_URL; ?>assets/js/<?php echo $city_file_name; ?>';

        fetch(jsonUrl)
            .then(response => response.json())
    .then(data => {
            data.forEach(item => {
            let p = item.province;
        let c = item.city;
        let a = item.area === "" ? "<?php echo $this->t('市本级/全区'); ?>" : item.area;

        if (!hierarchicalData[p]) hierarchicalData[p] = { lat: item.lat, lng: item.lng, cities: {} };
        if (!hierarchicalData[p].cities[c]) hierarchicalData[p].cities[c] = { lat: item.lat, lng: item.lng, areas: [] };
        if (a) hierarchicalData[p].cities[c].areas.push({ area: a, lat: item.lat, lng: item.lng });
    });

        // 初始化 A 方省份
        if(provSelect_a) {
            provSelect_a.innerHTML = '<option value=""><?php echo $this->t("- 选择省份 -"); ?></option>';
            for (const prov in hierarchicalData) provSelect_a.add(new Option(prov, prov));
            provSelect_a.addEventListener('change', function() {
                if(!citySelect_a || !distSelect_a) return;
                citySelect_a.innerHTML = '<option value=""><?php echo $this->t("- 选择城市 -"); ?></option>';
                distSelect_a.innerHTML = '<option value=""><?php echo $this->t("- 选择区县 -"); ?></option>';
                if (!this.value || !hierarchicalData[this.value]) return;
                if (locChina_a && locChina_a.checked) updateCoords_a(hierarchicalData[this.value].lat, hierarchicalData[this.value].lng);
                for (const city in hierarchicalData[this.value].cities) citySelect_a.add(new Option(city, city));
            });
        }
        if(citySelect_a) {
            citySelect_a.addEventListener('change', function() {
                if(!distSelect_a) return;
                distSelect_a.innerHTML = '<option value=""><?php echo $this->t("- 选择区县 -"); ?></option>';
                const selectedProv = provSelect_a.value;
                if (!selectedProv || !this.value || !hierarchicalData[selectedProv].cities[this.value]) return;
                const cityData = hierarchicalData[selectedProv].cities[this.value];
                if (locChina_a && locChina_a.checked) updateCoords_a(cityData.lat, cityData.lng);
                cityData.areas.forEach(item => {
                    const opt = new Option(item.area, item.area);
                opt.dataset.lat = item.lat; opt.dataset.lng = item.lng;
                distSelect_a.add(opt);
            });
            });
        }
        if(distSelect_a) {
            distSelect_a.addEventListener('change', function() {
                if (!this.value) return;
                const selectedOpt = this.options[this.selectedIndex];
                if (locChina_a && locChina_a.checked) updateCoords_a(selectedOpt.dataset.lat, selectedOpt.dataset.lng);
            });
        }

        // 初始化 B 方省份
        if(provSelect_b) {
            provSelect_b.innerHTML = '<option value=""><?php echo $this->t("- 选择省份 -"); ?></option>';
            for (const prov in hierarchicalData) provSelect_b.add(new Option(prov, prov));
            provSelect_b.addEventListener('change', function() {
                if(!citySelect_b || !distSelect_b) return;
                citySelect_b.innerHTML = '<option value=""><?php echo $this->t("- 选择城市 -"); ?></option>';
                distSelect_b.innerHTML = '<option value=""><?php echo $this->t("- 选择区县 -"); ?></option>';
                if (!this.value || !hierarchicalData[this.value]) return;
                if (locChina_b && locChina_b.checked) updateCoords_b(hierarchicalData[this.value].lat, hierarchicalData[this.value].lng);
                for (const city in hierarchicalData[this.value].cities) citySelect_b.add(new Option(city, city));
            });
        }
        if(citySelect_b) {
            citySelect_b.addEventListener('change', function() {
                if(!distSelect_b) return;
                distSelect_b.innerHTML = '<option value=""><?php echo $this->t("- 选择区县 -"); ?></option>';
                const selectedProv = provSelect_b.value;
                if (!selectedProv || !this.value || !hierarchicalData[selectedProv].cities[this.value]) return;
                const cityData = hierarchicalData[selectedProv].cities[this.value];
                if (locChina_b && locChina_b.checked) updateCoords_b(cityData.lat, cityData.lng);
                cityData.areas.forEach(item => {
                    const opt = new Option(item.area, item.area);
                opt.dataset.lat = item.lat; opt.dataset.lng = item.lng;
                distSelect_b.add(opt);
            });
            });
        }
        if(distSelect_b) {
            distSelect_b.addEventListener('change', function() {
                if (!this.value) return;
                const selectedOpt = this.options[this.selectedIndex];
                if (locChina_b && locChina_b.checked) updateCoords_b(selectedOpt.dataset.lat, selectedOpt.dataset.lng);
            });
        }
    })
    .catch(err => {
            if(provSelect_a) provSelect_a.innerHTML = '<option value=""><?php echo $this->t("城市数据加载失败"); ?></option>';
        if(provSelect_b) provSelect_b.innerHTML = '<option value=""><?php echo $this->t("城市数据加载失败"); ?></option>';
    });
    })();
</script>