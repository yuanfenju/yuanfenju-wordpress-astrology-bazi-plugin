<div class="yfj-form-container" data-module="<?php echo esc_attr($module_id); ?>">
    <div style="text-align: center; margin-bottom: 25px;">
        <h3 style="margin: 0 0 10px 0; color: var(--yfj-text-dark);"><?php echo $this->t('西方占星行运盘'); ?></h3>
        <p style="font-size: 13px; color: var(--yfj-text-body); margin: 0;">
            <?php echo $this->t('结合本命生命蓝图与特定时间的宇宙天象，洞察流年、流月甚至当下的运势起伏与突发事件。'); ?>
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
                    <span class="dashicons dashicons-clock"></span> <?php echo $this->t('推运时间'); ?>
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

        <button type="submit"><?php echo $this->t('推演行运盘'); ?></button>
    </form>

    <div class="yfj-loading" style="display:none;"><?php echo $this->t('正在计算行运轨迹...'); ?></div>
    <div class="yfj-result-area"></div>
</div>

<script>
    (function() {
        const locChina = document.getElementById('loc_china');
        const locCustom = document.getElementById('loc_custom');
        const cityArea = document.getElementById('yfj-city-area');
        const customArea = document.getElementById('yfj-custom-area');
        const provSelect = document.getElementById('yfj_province');
        const citySelect = document.getElementById('yfj_city');
        const distSelect = document.getElementById('yfj_district');

        function updateCoords(lat, lng) {
            let rawLat = document.getElementById('raw_latitude');
            let rawLng = document.getElementById('raw_longitude');
            if(rawLat) rawLat.value = lat;
            if(rawLng) rawLng.value = lng;
        }

        function toggleLoc() {
            if(!cityArea || !customArea) return;
            if (locChina.checked) {
                cityArea.style.display = 'block';
                customArea.style.display = 'none';
                if(distSelect) distSelect.dispatchEvent(new Event('change'));
            } else {
                cityArea.style.display = 'none';
                customArea.style.display = 'block';
                calcCustom();
            }
        }
        if(locChina) locChina.addEventListener('change', toggleLoc);
        if(locCustom) locCustom.addEventListener('change', toggleLoc);

        function calcCustom() {
            let dEl = document.getElementById('lng_deg'), mEl = document.getElementById('lng_min'), dirEl = document.getElementById('lng_dir');
            if(!dEl || !mEl || !dirEl) return;
            let lng = (parseFloat(dEl.value)||0) + (parseFloat(mEl.value)||0)/60;
            if (dirEl.value === 'W') lng = -lng;

            let latDEl = document.getElementById('lat_deg'), latMEl = document.getElementById('lat_min'), latDirEl = document.getElementById('lat_dir');
            if(!latDEl || !latMEl || !latDirEl) return;
            let lat = (parseFloat(latDEl.value)||0) + (parseFloat(latMEl.value)||0)/60;
            if (latDirEl.value === 'S') lat = -lat;

            updateCoords(lat.toFixed(6), lng.toFixed(6));
        }

        let l1 = document.getElementById('lng_deg'); if(l1) l1.addEventListener('change', function(){ if(locCustom && locCustom.checked) calcCustom(); });
        let l2 = document.getElementById('lng_min'); if(l2) l2.addEventListener('change', function(){ if(locCustom && locCustom.checked) calcCustom(); });
        let l3 = document.getElementById('lng_dir'); if(l3) l3.addEventListener('change', function(){ if(locCustom && locCustom.checked) calcCustom(); });
        let l4 = document.getElementById('lat_deg'); if(l4) l4.addEventListener('change', function(){ if(locCustom && locCustom.checked) calcCustom(); });
        let l5 = document.getElementById('lat_min'); if(l5) l5.addEventListener('change', function(){ if(locCustom && locCustom.checked) calcCustom(); });
        let l6 = document.getElementById('lat_dir'); if(l6) l6.addEventListener('change', function(){ if(locCustom && locCustom.checked) calcCustom(); });

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

        if(provSelect) {
            provSelect.innerHTML = '<option value=""><?php echo $this->t("- 选择省份 -"); ?></option>';
            for (const prov in hierarchicalData) provSelect.add(new Option(prov, prov));
            provSelect.addEventListener('change', function() {
                if(!citySelect || !distSelect) return;
                citySelect.innerHTML = '<option value=""><?php echo $this->t("- 选择城市 -"); ?></option>';
                distSelect.innerHTML = '<option value=""><?php echo $this->t("- 选择区县 -"); ?></option>';
                if (!this.value || !hierarchicalData[this.value]) return;
                if (locChina && locChina.checked) updateCoords(hierarchicalData[this.value].lat, hierarchicalData[this.value].lng);
                for (const city in hierarchicalData[this.value].cities) citySelect.add(new Option(city, city));
            });
        }
        if(citySelect) {
            citySelect.addEventListener('change', function() {
                if(!distSelect) return;
                distSelect.innerHTML = '<option value=""><?php echo $this->t("- 选择区县 -"); ?></option>';
                const selectedProv = provSelect.value;
                if (!selectedProv || !this.value || !hierarchicalData[selectedProv].cities[this.value]) return;
                const cityData = hierarchicalData[selectedProv].cities[this.value];
                if (locChina && locChina.checked) updateCoords(cityData.lat, cityData.lng);
                cityData.areas.forEach(item => {
                    const opt = new Option(item.area, item.area);
                opt.dataset.lat = item.lat; opt.dataset.lng = item.lng;
                distSelect.add(opt);
            });
            });
        }
        if(distSelect) {
            distSelect.addEventListener('change', function() {
                if (!this.value) return;
                const selectedOpt = this.options[this.selectedIndex];
                if (locChina && locChina.checked) updateCoords(selectedOpt.dataset.lat, selectedOpt.dataset.lng);
            });
        }
    })
    .catch(err => {
            if(provSelect) provSelect.innerHTML = '<option value=""><?php echo $this->t("城市数据加载失败"); ?></option>';
    });
    })();
</script>