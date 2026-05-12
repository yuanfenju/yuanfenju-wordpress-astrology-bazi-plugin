<div class="yfj-form-container" data-module="<?php echo esc_attr($module_id); ?>">
    <div style="text-align: center; margin-bottom: 25px;">
        <h3 style="margin: 0 0 10px 0; color: var(--yfj-text-dark);"><?php echo $this->t('西方占星月返照盘'); ?></h3>
        <p style="font-size: 13px; color: var(--yfj-text-body); margin: 0;">
            <?php echo $this->t('月返照盘是当天空中的月亮准确回到你出生那一刻月亮的度数时打出的一张星盘，用于预测未来28天左右的短期运势、情绪起伏和焦点事件。'); ?>
        </p>
    </div>

    <form class="yfj-ajax-form">
        <?php wp_nonce_field('yfj_nonce', 'yfj_nonce_field'); ?>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 20px; margin-bottom: 25px;">

            <div style="background: #f8fafc; padding: 20px; border-radius: 8px; border: 1px solid #e2e8f0;">
                <h4 style="margin-top: 0; color: #2563eb; border-bottom: 1px dashed #bfdbfe; padding-bottom: 10px; margin-bottom: 15px;">
                    <span class="dashicons dashicons-admin-users"></span> <?php echo $this->t('本命信息'); ?>
                </h4>

                <div style="display: flex; gap: 15px; margin-bottom: 15px; flex-wrap: wrap;">
                    <div class="form-group" style="flex: 1; min-width: 150px; margin-bottom: 0;">
                        <label><?php echo $this->t('命主姓名：'); ?></label>
                        <input type="text" name="name" required placeholder="<?php echo $this->t('请输入姓名'); ?>" value="<?php echo $this->t('求测者'); ?>">
                    </div>
                    <div class="form-group" style="flex: 1; min-width: 150px; margin-bottom: 0;">
                        <label><?php echo $this->t('性别：'); ?></label>
                        <div style="padding-top: 10px;">
                            <label style="display:inline; margin-right: 15px; font-weight: normal; cursor: pointer;">
                                <input type="radio" name="sex" value="0" checked> <?php echo $this->t('男'); ?>
                            </label>
                            <label style="display:inline; font-weight: normal; cursor: pointer;">
                                <input type="radio" name="sex" value="1"> <?php echo $this->t('女'); ?>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <label><?php echo $this->t('出生日期 (公历/阳历)：'); ?></label>
                    <div style="display: flex; gap: 10px;">
                        <select name="year" id="yfj_year" style="flex: 1.2;">
                            <?php $current_year = date('Y'); for($y=1930; $y<=($current_year + 5); $y++): ?>
                                <option value="<?php echo $y; ?>" <?php selected($y, 1990); ?>><?php echo $y; ?> <?php echo $this->t('年'); ?></option>
                            <?php endfor; ?>
                        </select>
                        <select name="month" id="yfj_month" style="flex: 1;">
                            <?php for($m=1; $m<=12; $m++): ?>
                                <option value="<?php echo $m; ?>"><?php echo $m; ?> <?php echo $this->t('月'); ?></option>
                            <?php endfor; ?>
                        </select>
                        <select name="day" id="yfj_day" style="flex: 1;">
                            <?php for($d=1; $d<=31; $d++): ?>
                                <option value="<?php echo $d; ?>"><?php echo $d; ?> <?php echo $this->t('日'); ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <label><?php echo $this->t('出生时辰：'); ?></label>
                    <div style="display: flex; gap: 10px;">
                        <select name="hours" id="yfj_hour" style="flex: 1;">
                            <?php for($h=0; $h<24; $h++): ?>
                                <option value="<?php echo $h; ?>" <?php selected($h, 12); ?>><?php echo sprintf("%02d", $h); ?> <?php echo $this->t('时'); ?></option>
                            <?php endfor; ?>
                        </select>
                        <select name="minute" id="yfj_minute" style="flex: 1;">
                            <?php for($min=0; $min<60; $min++): ?>
                                <option value="<?php echo $min; ?>"><?php echo sprintf("%02d", $min); ?> <?php echo $this->t('分'); ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <label><?php echo $this->t('出生时区：'); ?></label>
                    <select name="timezone" style="width: 100%;">
                        <option value="Asia/Shanghai" selected><?php echo $this->t('中国标准时间'); ?></option>
                        <option value="Asia/Taipei"><?php echo $this->t('台北'); ?></option>
                        <option value="Asia/Hong_Kong"><?php echo $this->t('香港'); ?></option>
                        <option value="Asia/Macau"><?php echo $this->t('澳门'); ?></option>
                        <option value="Asia/Tokyo"><?php echo $this->t('日本'); ?></option>
                        <option value="Asia/Seoul"><?php echo $this->t('韩国'); ?></option>
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
                            <input type="radio" name="loc_type" value="china" id="loc_china" checked> <?php echo $this->t('中国地区'); ?>
                        </label>
                        <label style="display:inline; font-weight: normal; cursor: pointer;">
                            <input type="radio" name="loc_type" value="custom" id="loc_custom"> <?php echo $this->t('自定义经纬度'); ?>
                        </label>
                    </div>

                    <input type="hidden" name="longitude" id="raw_longitude" value="116.405285">
                    <input type="hidden" name="latitude" id="raw_latitude" value="39.904989">

                    <div id="yfj-city-area" style="padding-top: 10px; border-top: 1px dashed #cbd5e1; margin-top: 10px;">
                        <div style="display: flex; gap: 10px;">
                            <select id="yfj_province" style="flex: 1;"><option value=""><?php echo $this->t('加载中...'); ?></option></select>
                            <select id="yfj_city" style="flex: 1;"><option value=""><?php echo $this->t('请选择市'); ?></option></select>
                            <select id="yfj_district" style="flex: 1;"><option value=""><?php echo $this->t('请选择区'); ?></option></select>
                        </div>
                    </div>

                    <div id="yfj-custom-area" style="display: none; padding-top: 10px; border-top: 1px dashed #cbd5e1; margin-top: 10px;">
                        <div style="display: flex; gap: 10px; margin-bottom: 10px;">
                            <span style="width: 45px; font-size: 15px; display: flex; align-items: center; color: #293d51;"><?php echo $this->t('经度：'); ?></span>
                            <select id="lng_deg" style="flex: 1;">
                                <?php for($i=0; $i<=180; $i++): ?><option value="<?php echo $i; ?>" <?php selected($i, 116); ?>><?php echo $i; ?>°</option><?php endfor; ?>
                            </select>
                            <select id="lng_min" style="flex: 1;">
                                <?php for($i=0; $i<=59; $i++): ?><option value="<?php echo $i; ?>" <?php selected($i, 24); ?>><?php echo sprintf("%02d", $i); ?>′</option><?php endfor; ?>
                            </select>
                            <select id="lng_dir" style="flex: 1;">
                                <option value="E" selected><?php echo $this->t('东经 (E)'); ?></option>
                                <option value="W"><?php echo $this->t('西经 (W)'); ?></option>
                            </select>
                        </div>
                        <div style="display: flex; gap: 10px;">
                            <span style="width: 45px; font-size: 15px; display: flex; align-items: center; color: #293d51"><?php echo $this->t('纬度：'); ?></span>
                            <select id="lat_deg" style="flex: 1;">
                                <?php for($i=0; $i<=90; $i++): ?><option value="<?php echo $i; ?>" <?php selected($i, 39); ?>><?php echo $i; ?>°</option><?php endfor; ?>
                            </select>
                            <select id="lat_min" style="flex: 1;">
                                <?php for($i=0; $i<=59; $i++): ?><option value="<?php echo $i; ?>" <?php selected($i, 54); ?>><?php echo sprintf("%02d", $i); ?>′</option><?php endfor; ?>
                            </select>
                            <select id="lat_dir" style="flex: 1;">
                                <option value="N" selected><?php echo $this->t('北纬 (N)'); ?></option>
                                <option value="S"><?php echo $this->t('南纬 (S)'); ?></option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div style="background: #fdfaf6; padding: 20px; border-radius: 8px; border: 1px solid #fce7f3;">
                <h4 style="margin-top: 0; color: #d97706; border-bottom: 1px dashed #fcd34d; padding-bottom: 10px; margin-bottom: 15px;">
                    <span class="dashicons dashicons-clock"></span> <?php echo $this->t('月返照年份与地点'); ?>
                </h4>

                <div class="form-group" style="margin-bottom: 15px;">
                    <label><?php echo $this->t('目标推运日期：'); ?></label>
                    <div style="display: flex; gap: 10px;">
                        <select name="target_year" id="yfj_target_year" style="flex: 1.2;">
                            <?php for($y=1930; $y<=($current_year + 20); $y++): ?>
                                <option value="<?php echo $y; ?>" <?php selected($y, $current_year); ?>><?php echo $y; ?> <?php echo $this->t('年'); ?></option>
                            <?php endfor; ?>
                        </select>
                        <select name="target_month" id="yfj_target_month" style="flex: 1;">
                            <?php $current_month = date('n'); for($m=1; $m<=12; $m++): ?>
                                <option value="<?php echo $m; ?>" <?php selected($m, $current_month); ?>><?php echo $m; ?> <?php echo $this->t('月'); ?></option>
                            <?php endfor; ?>
                        </select>
                        <select name="target_day" id="yfj_target_day" style="flex: 1;">
                            <?php $current_day = date('j'); for($d=1; $d<=31; $d++): ?>
                                <option value="<?php echo $d; ?>" <?php selected($d, $current_day); ?>><?php echo $d; ?> <?php echo $this->t('日'); ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <label><?php echo $this->t('目标推运时辰：'); ?></label>
                    <div style="display: flex; gap: 10px;">
                        <select name="target_hours" id="yfj_target_hour" style="flex: 1;">
                            <?php $current_hour = date('H'); for($h=0; $h<24; $h++): ?>
                                <option value="<?php echo $h; ?>" <?php selected($h, $current_hour); ?>><?php echo sprintf("%02d", $h); ?> <?php echo $this->t('时'); ?></option>
                            <?php endfor; ?>
                        </select>
                        <select name="target_minute" id="yfj_target_minute" style="flex: 1;">
                            <?php for($min=0; $min<60; $min++): ?>
                                <option value="<?php echo $min; ?>"><?php echo sprintf("%02d", $min); ?> <?php echo $this->t('分'); ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <label><?php echo $this->t('返照地时区：'); ?></label>
                    <select name="target_timezone" style="width: 100%;">
                        <option value="Asia/Shanghai" selected><?php echo $this->t('中国标准时间'); ?></option>
                        <option value="Asia/Taipei"><?php echo $this->t('台北'); ?></option>
                        <option value="Asia/Hong_Kong"><?php echo $this->t('香港'); ?></option>
                        <option value="Asia/Macau"><?php echo $this->t('澳门'); ?></option>
                        <option value="Asia/Tokyo"><?php echo $this->t('日本'); ?></option>
                        <option value="Asia/Seoul"><?php echo $this->t('韩国'); ?></option>
                        <option value="America/New_York"><?php echo $this->t('美东'); ?></option>
                        <option value="America/Los_Angeles"><?php echo $this->t('美西'); ?></option>
                        <option value="Europe/London"><?php echo $this->t('英国'); ?></option>
                        <option value="Europe/Paris"><?php echo $this->t('法国'); ?></option>
                        <option value="Australia/Sydney"><?php echo $this->t('悉尼'); ?></option>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 0; background: #fff; padding: 15px; border: 1px solid #fce7f3; border-radius: 6px;">
                    <label style="margin-bottom: 10px;"><?php echo $this->t('返照发生地点：'); ?></label>
                    <div style="padding-top: 5px; margin-bottom: 10px;">
                        <label style="display:inline; margin-right: 15px; font-weight: normal; cursor: pointer;">
                            <input type="radio" name="target_loc_type" value="china" id="loc_china_b" checked> <?php echo $this->t('中国地区'); ?>
                        </label>
                        <label style="display:inline; font-weight: normal; cursor: pointer;">
                            <input type="radio" name="target_loc_type" value="custom" id="loc_custom_b"> <?php echo $this->t('自定义经纬度'); ?>
                        </label>
                    </div>

                    <input type="hidden" name="target_longitude" id="raw_longitude_b" value="116.405285">
                    <input type="hidden" name="target_latitude" id="raw_latitude_b" value="39.904989">

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

        <button type="submit"><?php echo $this->t('推演月返照盘'); ?></button>
    </form>

    <div class="yfj-loading" style="display:none;"><?php echo $this->t('正在计算年度运势星象...'); ?></div>
    <div class="yfj-result-area"></div>
</div>

<script>
    (function() {
        // ================= 本命所在地逻辑 (A) =================
        const locChina = document.getElementById('loc_china'), locCustom = document.getElementById('loc_custom');
        const cityArea = document.getElementById('yfj-city-area'), customArea = document.getElementById('yfj-custom-area');
        const provSelect = document.getElementById('yfj_province'), citySelect = document.getElementById('yfj_city'), distSelect = document.getElementById('yfj_district');

        function updateCoords(lat, lng) {
            let rLat = document.getElementById('raw_latitude'), rLng = document.getElementById('raw_longitude');
            if(rLat) rLat.value = lat; if(rLng) rLng.value = lng;
        }

        function toggleLoc() {
            if(!cityArea || !customArea) return;
            if (locChina.checked) {
                cityArea.style.display = 'block'; customArea.style.display = 'none';
                if(distSelect) distSelect.dispatchEvent(new Event('change'));
            } else {
                cityArea.style.display = 'none'; customArea.style.display = 'block'; calcCustom();
            }
        }
        if(locChina) locChina.addEventListener('change', toggleLoc);
        if(locCustom) locCustom.addEventListener('change', toggleLoc);

        function calcCustom() {
            let dEl = document.getElementById('lng_deg'), mEl = document.getElementById('lng_min'), dirEl = document.getElementById('lng_dir');
            if(!dEl || !mEl || !dirEl) return;
            let lng = (parseFloat(dEl.value)||0) + (parseFloat(mEl.value)||0)/60; if (dirEl.value === 'W') lng = -lng;
            let latDEl = document.getElementById('lat_deg'), latMEl = document.getElementById('lat_min'), latDirEl = document.getElementById('lat_dir');
            if(!latDEl || !latMEl || !latDirEl) return;
            let lat = (parseFloat(latDEl.value)||0) + (parseFloat(latMEl.value)||0)/60; if (latDirEl.value === 'S') lat = -lat;
            updateCoords(lat.toFixed(6), lng.toFixed(6));
        }
        ['lng_deg','lng_min','lng_dir','lat_deg','lat_min','lat_dir'].forEach(id => {
            let el = document.getElementById(id); if(el) el.addEventListener('change', function(){ if(locCustom && locCustom.checked) calcCustom(); });
    });

        // ================= 返照所在地逻辑 (B) =================
        const locChina_b = document.getElementById('loc_china_b'), locCustom_b = document.getElementById('loc_custom_b');
        const cityArea_b = document.getElementById('yfj_city_area_b'), customArea_b = document.getElementById('yfj_custom_area_b');
        const provSelect_b = document.getElementById('yfj_province_b'), citySelect_b = document.getElementById('yfj_city_b'), distSelect_b = document.getElementById('yfj_district_b');

        function updateCoords_b(lat, lng) {
            let rLat = document.getElementById('raw_latitude_b'), rLng = document.getElementById('raw_longitude_b');
            if(rLat) rLat.value = lat; if(rLng) rLng.value = lng;
        }

        function toggleLoc_b() {
            if(!cityArea_b || !customArea_b) return;
            if (locChina_b.checked) {
                cityArea_b.style.display = 'block'; customArea_b.style.display = 'none';
                if(distSelect_b) distSelect_b.dispatchEvent(new Event('change'));
            } else {
                cityArea_b.style.display = 'none'; customArea_b.style.display = 'block'; calcCustom_b();
            }
        }
        if(locChina_b) locChina_b.addEventListener('change', toggleLoc_b);
        if(locCustom_b) locCustom_b.addEventListener('change', toggleLoc_b);

        function calcCustom_b() {
            let dEl = document.getElementById('lng_deg_b'), mEl = document.getElementById('lng_min_b'), dirEl = document.getElementById('lng_dir_b');
            if(!dEl || !mEl || !dirEl) return;
            let lng = (parseFloat(dEl.value)||0) + (parseFloat(mEl.value)||0)/60; if (dirEl.value === 'W') lng = -lng;
            let latDEl = document.getElementById('lat_deg_b'), latMEl = document.getElementById('lat_min_b'), latDirEl = document.getElementById('lat_dir_b');
            if(!latDEl || !latMEl || !latDirEl) return;
            let lat = (parseFloat(latDEl.value)||0) + (parseFloat(latMEl.value)||0)/60; if (latDirEl.value === 'S') lat = -lat;
            updateCoords_b(lat.toFixed(6), lng.toFixed(6));
        }
        ['lng_deg_b','lng_min_b','lng_dir_b','lat_deg_b','lat_min_b','lat_dir_b'].forEach(id => {
            let el = document.getElementById(id); if(el) el.addEventListener('change', function(){ if(locCustom_b && locCustom_b.checked) calcCustom_b(); });
    });

        // ================= 加载通用省市级联 JSON =================
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
            let p = item.province, c = item.city, a = item.area === "" ? "<?php echo $this->t('市本级/全区'); ?>" : item.area;
        if (!hierarchicalData[p]) hierarchicalData[p] = { lat: item.lat, lng: item.lng, cities: {} };
        if (!hierarchicalData[p].cities[c]) hierarchicalData[p].cities[c] = { lat: item.lat, lng: item.lng, areas: [] };
        if (a) hierarchicalData[p].cities[c].areas.push({ area: a, lat: item.lat, lng: item.lng });
    });

        // 初始化本命地
        if(provSelect) {
            provSelect.innerHTML = '<option value=""><?php echo $this->t("- 选择省份 -"); ?></option>';
            for (const prov in hierarchicalData) provSelect.add(new Option(prov, prov));
            provSelect.addEventListener('change', function() {
                citySelect.innerHTML = '<option value=""><?php echo $this->t("- 选择城市 -"); ?></option>';
                distSelect.innerHTML = '<option value=""><?php echo $this->t("- 选择区县 -"); ?></option>';
                if (!this.value) return;
                if (locChina.checked) updateCoords(hierarchicalData[this.value].lat, hierarchicalData[this.value].lng);
                for (const city in hierarchicalData[this.value].cities) citySelect.add(new Option(city, city));
            });
        }
        if(citySelect) {
            citySelect.addEventListener('change', function() {
                distSelect.innerHTML = '<option value=""><?php echo $this->t("- 选择区县 -"); ?></option>';
                if (!this.value) return;
                const cData = hierarchicalData[provSelect.value].cities[this.value];
                if (locChina.checked) updateCoords(cData.lat, cData.lng);
                cData.areas.forEach(item => {
                    const opt = new Option(item.area, item.area); opt.dataset.lat = item.lat; opt.dataset.lng = item.lng; distSelect.add(opt);
            });
            });
        }
        if(distSelect) {
            distSelect.addEventListener('change', function() {
                if (!this.value) return;
                if (locChina.checked) updateCoords(this.options[this.selectedIndex].dataset.lat, this.options[this.selectedIndex].dataset.lng);
            });
        }

        // 初始化返照地
        if(provSelect_b) {
            provSelect_b.innerHTML = '<option value=""><?php echo $this->t("- 选择省份 -"); ?></option>';
            for (const prov in hierarchicalData) provSelect_b.add(new Option(prov, prov));
            provSelect_b.addEventListener('change', function() {
                citySelect_b.innerHTML = '<option value=""><?php echo $this->t("- 选择城市 -"); ?></option>';
                distSelect_b.innerHTML = '<option value=""><?php echo $this->t("- 选择区县 -"); ?></option>';
                if (!this.value) return;
                if (locChina_b.checked) updateCoords_b(hierarchicalData[this.value].lat, hierarchicalData[this.value].lng);
                for (const city in hierarchicalData[this.value].cities) citySelect_b.add(new Option(city, city));
            });
        }
        if(citySelect_b) {
            citySelect_b.addEventListener('change', function() {
                distSelect_b.innerHTML = '<option value=""><?php echo $this->t("- 选择区县 -"); ?></option>';
                if (!this.value) return;
                const cData = hierarchicalData[provSelect_b.value].cities[this.value];
                if (locChina_b.checked) updateCoords_b(cData.lat, cData.lng);
                cData.areas.forEach(item => {
                    const opt = new Option(item.area, item.area); opt.dataset.lat = item.lat; opt.dataset.lng = item.lng; distSelect_b.add(opt);
            });
            });
        }
        if(distSelect_b) {
            distSelect_b.addEventListener('change', function() {
                if (!this.value) return;
                if (locChina_b.checked) updateCoords_b(this.options[this.selectedIndex].dataset.lat, this.options[this.selectedIndex].dataset.lng);
            });
        }
    });
    })();
</script>