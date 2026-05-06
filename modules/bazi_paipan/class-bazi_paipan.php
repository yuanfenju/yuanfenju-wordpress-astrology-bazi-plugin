<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// 注意这里的类名，要和主文件里 YFJ_Module_ + ucfirst('bazi_paipan') 对应
class YFJ_Module_Bazi_paipan extends YFJ_Base_Module {

    public function __construct() {
        $this->module_id = 'bazi_paipan';             // 1. 修改模块 ID
        $this->shortcode = 'yfj_bazi_paipan';         // 2. 修改短代码
        $this->api_endpoint = '/v1/Bazi/paipan';      // 3. 配置对应的真实 API 接口

        parent::__construct();
    }

    /**
     * 专属数据拦截与加工引擎 (重写基类方法)
     * 在这里可以对 API 返回的原始数据进行格式化、过滤或追加二次计算的自定义数据。
     */
    protected function process_api_data($raw_data) {
        // 1. 确保数据是数组格式，防止报错
        if (is_array($raw_data)) {
            //如果 API 没返回格局，给个默认的兜底值
            if (!isset($raw_data['base_info']['zhengge'])) {
                $raw_data['base_info']['zhengge'] = '未入格';
            }
        }

        //将处理后的数据返回给 views/result.php 进行渲染
        return $raw_data;
    }

    /**
     * 沙盒模式下的模拟数据
     */
    protected function get_demo_data($post_data) {
        //获取当前后台设置的语言
        $lang = get_option('yfj_language', 'zh-cn');

        // 1. 把你的完整 JSON 粘贴到单引号里面（要考虑到zh-cn 和 zh-tw）
        $demo_json_zh_cn = '{"errcode":0,"errmsg":"请求成功","notice":"本次测算结果仅供娱乐使用，请勿用于封建迷信和违法用途。","data":{"base_info":{"sex":"坤造","name":"张三","gongli":"1988年1月8日12时20分","nongli":"丁卯年 十一月 十九日 午时","qiyun":"9年1月21天起运","jiaoyun":"1997年1月12日11时31分38秒","zhengge":"正官格"},"bazi_info":{"kw":"子丑","tg_cg_god":["正财","劫财","日元","偏财"],"bazi":["丁卯","癸丑","壬戌","丙午"],"dz_cg":["乙","己|癸|辛","戊|辛|丁","丁|己"],"dz_cg_god":["伤官","正官|劫财|正印","七杀|正印|正财","正财|正官"],"day_cs":["死","衰","冠带","胎"],"na_yin":["炉中火","桑柘木","大海水","天河水"]},"dayun_info":{"big_god":["食神","伤官","偏财","正财","七杀","正官","偏印","正印","比肩","劫财","食神","伤官"],"big":["甲寅","乙卯","丙辰","丁巳","戊午","己未","庚申","辛酉","壬戌","癸亥","甲子","乙丑"],"big_cs":["病","死","墓","绝","胎","养","长生","沐浴","冠带","临官","帝旺","衰"],"xu_sui":[10,20,30,40,50,59,69,79,89,99,109,119],"big_start_year":[1997,2007,2017,2027,2037,2046,2056,2066,2076,2086,2096,2106],"big_start_year_liu_nian":"","big_end_year":[2006,2016,2026,2036,2046,2055,2065,2075,2085,2095,2105,2115],"years_info0":[{"year_char":"丙子"},{"year_char":"丙戌"},{"year_char":"丙申"},{"year_char":"丙午"},{"year_char":"丙辰"},{"year_char":"乙丑"},{"year_char":"乙亥"},{"year_char":"乙酉"},{"year_char":"乙未"},{"year_char":"乙巳"},{"year_char":"乙卯"},{"year_char":"乙丑"}],"years_info1":[{"year_char":"丁丑"},{"year_char":"丁亥"},{"year_char":"丁酉"},{"year_char":"丁未"},{"year_char":"丁巳"},{"year_char":"丙寅"},{"year_char":"丙子"},{"year_char":"丙戌"},{"year_char":"丙申"},{"year_char":"丙午"},{"year_char":"丙辰"},{"year_char":"丙寅"}],"years_info2":[{"year_char":"戊寅"},{"year_char":"戊子"},{"year_char":"戊戌"},{"year_char":"戊申"},{"year_char":"戊午"},{"year_char":"丁卯"},{"year_char":"丁丑"},{"year_char":"丁亥"},{"year_char":"丁酉"},{"year_char":"丁未"},{"year_char":"丁巳"},{"year_char":"丁卯"}],"years_info3":[{"year_char":"己卯"},{"year_char":"己丑"},{"year_char":"己亥"},{"year_char":"己酉"},{"year_char":"己未"},{"year_char":"戊辰"},{"year_char":"戊寅"},{"year_char":"戊子"},{"year_char":"戊戌"},{"year_char":"戊申"},{"year_char":"戊午"},{"year_char":"戊辰"}],"years_info4":[{"year_char":"庚辰"},{"year_char":"庚寅"},{"year_char":"庚子"},{"year_char":"庚戌"},{"year_char":"庚申"},{"year_char":"己巳"},{"year_char":"己卯"},{"year_char":"己丑"},{"year_char":"己亥"},{"year_char":"己酉"},{"year_char":"己未"},{"year_char":"己巳"}],"years_info5":[{"year_char":"辛巳"},{"year_char":"辛卯"},{"year_char":"辛丑"},{"year_char":"辛亥"},{"year_char":"辛酉"},{"year_char":"庚午"},{"year_char":"庚辰"},{"year_char":"庚寅"},{"year_char":"庚子"},{"year_char":"庚戌"},{"year_char":"庚申"},{"year_char":"庚午"}],"years_info6":[{"year_char":"壬午"},{"year_char":"壬辰"},{"year_char":"壬寅"},{"year_char":"壬子"},{"year_char":"壬戌"},{"year_char":"辛未"},{"year_char":"辛巳"},{"year_char":"辛卯"},{"year_char":"辛丑"},{"year_char":"辛亥"},{"year_char":"辛酉"},{"year_char":"辛未"}],"years_info7":[{"year_char":"癸未"},{"year_char":"癸巳"},{"year_char":"癸卯"},{"year_char":"癸丑"},{"year_char":"癸亥"},{"year_char":"壬申"},{"year_char":"壬午"},{"year_char":"壬辰"},{"year_char":"壬寅"},{"year_char":"壬子"},{"year_char":"壬戌"},{"year_char":"壬申"}],"years_info8":[{"year_char":"甲申"},{"year_char":"甲午"},{"year_char":"甲辰"},{"year_char":"甲寅"},{"year_char":"甲子"},{"year_char":"癸酉"},{"year_char":"癸未"},{"year_char":"癸巳"},{"year_char":"癸卯"},{"year_char":"癸丑"},{"year_char":"癸亥"},{"year_char":"癸酉"}],"years_info9":[{"year_char":"乙酉"},{"year_char":"乙未"},{"year_char":"乙巳"},{"year_char":"乙卯"},{"year_char":"乙丑"},{"year_char":"甲戌"},{"year_char":"甲申"},{"year_char":"甲午"},{"year_char":"甲辰"},{"year_char":"甲寅"},{"year_char":"甲子"},{"year_char":"甲戌"}]},"start_info":{"jishen":["天乙贵人 太极贵人 桃花","金舆 寡宿 空亡 天狗 吊客","日德 天罗 阴差阳错 童子煞 元辰 空亡","天厨贵人 将星 天喜 飞刃 勾绞煞 六厄"],"xz":"摩羯座","sx":"兔"},"detail_info":{"zhuxing":{"year":"正财","month":"劫财","day":"日元","hour":"偏财"},"sizhu":{"year":{"tg":"丁","dz":"卯"},"month":{"tg":"癸","dz":"丑"},"day":{"tg":"壬","dz":"戌"},"hour":{"tg":"丙","dz":"午"}},"canggan":{"year":["乙"],"month":["己","癸","辛"],"day":["戊","辛","丁"],"hour":["丁","己"]},"fuxing":{"year":["伤官"],"month":["正官","劫财","正印"],"day":["七杀","正印","正财"],"hour":["正财","正官"]},"xingyun":{"year":"死","month":"衰","day":"冠带","hour":"胎"},"zizuo":{"year":"病","month":"冠带","day":"冠带","hour":"帝旺"},"kongwang":{"year":"戌亥","month":"寅卯","day":"子丑","hour":"寅卯"},"nayin":{"year":"炉中火","month":"桑柘木","day":"大海水","hour":"天河水"},"shensha":{"year":"天乙贵人 太极贵人 桃花","month":"金舆 寡宿 空亡 天狗 吊客","day":"日德 天罗 阴差阳错 童子煞 元辰 空亡","hour":"天厨贵人 将星 天喜 飞刃 勾绞煞 六厄"},"dayunshensha":[{"tgdz":"甲寅","shensha":"福星贵人 文昌贵人 天厨贵人 日德 "},{"tgdz":"乙卯","shensha":"天乙贵人 天德合 月德合 福星贵人 德秀贵人 桃花 "},{"tgdz":"丙辰","shensha":"福星贵人 日德 "},{"tgdz":"丁巳","shensha":"天乙贵人 太极贵人 亡神 "},{"tgdz":"戊午","shensha":"将星 飞刃 "},{"tgdz":"己未","shensha":"太极贵人 福星贵人 国印贵人 "},{"tgdz":"庚申","shensha":"天德贵人 月德贵人 太极贵人 德秀贵人 驿马 "},{"tgdz":"辛酉","shensha":"三奇贵人 德秀贵人 "},{"tgdz":"壬戌","shensha":"华盖 日德 "},{"tgdz":"癸亥","shensha":"禄神 劫煞 天罗地网 流霞 "},{"tgdz":"甲子","shensha":"太极贵人 福星贵人 天医 进神 羊刃 血刃 红艳煞 空亡 "},{"tgdz":"乙丑","shensha":"天德合 月德合 福星贵人 德秀贵人 金舆 空亡 "}]}}}';
        $demo_json_zh_tw = '{"errcode":0,"errmsg":"請求成功","notice":"本次測算結果僅供娛樂使用，請勿用於封建迷信和違法用途。","data":{"base_info":{"sex":"坤造","name":"張三","gongli":"1988年1月8日12時20分","nongli":"丁卯年 十一月 十九日 午時","qiyun":"9年1月21天起運","jiaoyun":"1997年1月12日11時31分38秒","zhengge":"正官格"},"bazi_info":{"kw":"子丑","tg_cg_god":["正財","劫財","日元","偏財"],"bazi":["丁卯","癸丑","壬戌","丙午"],"dz_cg":["乙","己|癸|辛","戊|辛|丁","丁|己"],"dz_cg_god":["傷官","正官|劫財|正印","七殺|正印|正財","正財|正官"],"day_cs":["死","衰","冠帶","胎"],"na_yin":["爐中火","桑柘木","大海水","天河水"]},"dayun_info":{"big_god":["食神","傷官","偏財","正財","七殺","正官","偏印","正印","比肩","劫財","食神","傷官"],"big":["甲寅","乙卯","丙辰","丁巳","戊午","己未","庚申","辛酉","壬戌","癸亥","甲子","乙丑"],"big_cs":["病","死","墓","絕","胎","養","長生","沐浴","冠帶","臨官","帝旺","衰"],"xu_sui":[10,20,30,40,50,59,69,79,89,99,109,119],"big_start_year":[1997,2007,2017,2027,2037,2046,2056,2066,2076,2086,2096,2106],"big_start_year_liu_nian":"","big_end_year":[2006,2016,2026,2036,2046,2055,2065,2075,2085,2095,2105,2115],"years_info0":[{"year_char":"丙子"},{"year_char":"丙戌"},{"year_char":"丙申"},{"year_char":"丙午"},{"year_char":"丙辰"},{"year_char":"乙丑"},{"year_char":"乙亥"},{"year_char":"乙酉"},{"year_char":"乙未"},{"year_char":"乙巳"},{"year_char":"乙卯"},{"year_char":"乙丑"}],"years_info1":[{"year_char":"丁丑"},{"year_char":"丁亥"},{"year_char":"丁酉"},{"year_char":"丁未"},{"year_char":"丁巳"},{"year_char":"丙寅"},{"year_char":"丙子"},{"year_char":"丙戌"},{"year_char":"丙申"},{"year_char":"丙午"},{"year_char":"丙辰"},{"year_char":"丙寅"}],"years_info2":[{"year_char":"戊寅"},{"year_char":"戊子"},{"year_char":"戊戌"},{"year_char":"戊申"},{"year_char":"戊午"},{"year_char":"丁卯"},{"year_char":"丁丑"},{"year_char":"丁亥"},{"year_char":"丁酉"},{"year_char":"丁未"},{"year_char":"丁巳"},{"year_char":"丁卯"}],"years_info3":[{"year_char":"己卯"},{"year_char":"己丑"},{"year_char":"己亥"},{"year_char":"己酉"},{"year_char":"己未"},{"year_char":"戊辰"},{"year_char":"戊寅"},{"year_char":"戊子"},{"year_char":"戊戌"},{"year_char":"戊申"},{"year_char":"戊午"},{"year_char":"戊辰"}],"years_info4":[{"year_char":"庚辰"},{"year_char":"庚寅"},{"year_char":"庚子"},{"year_char":"庚戌"},{"year_char":"庚申"},{"year_char":"己巳"},{"year_char":"己卯"},{"year_char":"己丑"},{"year_char":"己亥"},{"year_char":"己酉"},{"year_char":"己未"},{"year_char":"己巳"}],"years_info5":[{"year_char":"辛巳"},{"year_char":"辛卯"},{"year_char":"辛丑"},{"year_char":"辛亥"},{"year_char":"辛酉"},{"year_char":"庚午"},{"year_char":"庚辰"},{"year_char":"庚寅"},{"year_char":"庚子"},{"year_char":"庚戌"},{"year_char":"庚申"},{"year_char":"庚午"}],"years_info6":[{"year_char":"壬午"},{"year_char":"壬辰"},{"year_char":"壬寅"},{"year_char":"壬子"},{"year_char":"壬戌"},{"year_char":"辛未"},{"year_char":"辛巳"},{"year_char":"辛卯"},{"year_char":"辛丑"},{"year_char":"辛亥"},{"year_char":"辛酉"},{"year_char":"辛未"}],"years_info7":[{"year_char":"癸未"},{"year_char":"癸巳"},{"year_char":"癸卯"},{"year_char":"癸丑"},{"year_char":"癸亥"},{"year_char":"壬申"},{"year_char":"壬午"},{"year_char":"壬辰"},{"year_char":"壬寅"},{"year_char":"壬子"},{"year_char":"壬戌"},{"year_char":"壬申"}],"years_info8":[{"year_char":"甲申"},{"year_char":"甲午"},{"year_char":"甲辰"},{"year_char":"甲寅"},{"year_char":"甲子"},{"year_char":"癸酉"},{"year_char":"癸未"},{"year_char":"癸巳"},{"year_char":"癸卯"},{"year_char":"癸丑"},{"year_char":"癸亥"},{"year_char":"癸酉"}],"years_info9":[{"year_char":"乙酉"},{"year_char":"乙未"},{"year_char":"乙巳"},{"year_char":"乙卯"},{"year_char":"乙丑"},{"year_char":"甲戌"},{"year_char":"甲申"},{"year_char":"甲午"},{"year_char":"甲辰"},{"year_char":"甲寅"},{"year_char":"甲子"},{"year_char":"甲戌"}]},"start_info":{"jishen":["天乙貴人 太極貴人 桃花","金輿 寡宿 空亡 天狗 吊客","日德 天羅 陰差陽錯 童子煞 元辰 空亡","天廚貴人 將星 天喜 飛刃 勾絞煞 六厄"],"xz":"摩羯座","sx":"兔"},"detail_info":{"zhuxing":{"year":"正財","month":"劫財","day":"日元","hour":"偏財"},"sizhu":{"year":{"tg":"丁","dz":"卯"},"month":{"tg":"癸","dz":"丑"},"day":{"tg":"壬","dz":"戌"},"hour":{"tg":"丙","dz":"午"}},"canggan":{"year":["乙"],"month":["己","癸","辛"],"day":["戊","辛","丁"],"hour":["丁","己"]},"fuxing":{"year":["傷官"],"month":["正官","劫財","正印"],"day":["七殺","正印","正財"],"hour":["正財","正官"]},"xingyun":{"year":"死","month":"衰","day":"冠帶","hour":"胎"},"zizuo":{"year":"病","month":"冠帶","day":"冠帶","hour":"帝旺"},"kongwang":{"year":"戌亥","month":"寅卯","day":"子丑","hour":"寅卯"},"nayin":{"year":"爐中火","month":"桑柘木","day":"大海水","hour":"天河水"},"shensha":{"year":"天乙貴人 太極貴人 桃花","month":"金輿 寡宿 空亡 天狗 吊客","day":"日德 天羅 陰差陽錯 童子煞 元辰 空亡","hour":"天廚貴人 將星 天喜 飛刃 勾絞煞 六厄"},"dayunshensha":[{"tgdz":"甲寅","shensha":"福星貴人 文昌貴人 天廚貴人 日德 "},{"tgdz":"乙卯","shensha":"天乙貴人 天德合 月德合 福星貴人 德秀貴人 桃花 "},{"tgdz":"丙辰","shensha":"福星貴人 日德 "},{"tgdz":"丁巳","shensha":"天乙貴人 太極貴人 亡神 "},{"tgdz":"戊午","shensha":"將星 飛刃 "},{"tgdz":"己未","shensha":"太極貴人 福星貴人 國印貴人 "},{"tgdz":"庚申","shensha":"天德貴人 月德貴人 太極貴人 德秀貴人 驛馬 "},{"tgdz":"辛酉","shensha":"三奇貴人 德秀貴人 "},{"tgdz":"壬戌","shensha":"華蓋 日德 "},{"tgdz":"癸亥","shensha":"祿神 劫煞 天羅地網 流霞 "},{"tgdz":"甲子","shensha":"太極貴人 福星貴人 天醫 進神 羊刃 血刃 紅艷煞 空亡 "},{"tgdz":"乙丑","shensha":"天德合 月德合 福星貴人 德秀貴人 金輿 空亡 "}]}}}';
        $demo_name_zh_cn = "测试数据";
        $demo_name_zh_tw = "測試數據";

        if ($lang == 'zh-tw') {
            $demo_json = $demo_json_zh_tw;
            $demo_name = $demo_name_zh_tw;
        } else {
            $demo_json = $demo_json_zh_cn;
            $demo_name = $demo_name_zh_cn;
        }

        // 2. 解析为 PHP 数组
        $demo_array_json = json_decode($demo_json, true);

        // 3. 【核心提取】剥离外层的 errcode 状态，只提取 data 节点内容
        $data = $demo_array_json['data'] ?? [];

        // 4. 【测试数据】
        if (!empty($post_data['name'])) {
            $data['base_info']['name'] = $demo_name;
        }

        // 5. 返回处理好的干净数据给 process_api_data
        return $data;
    }
}