<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// 注意这里的类名，要和主文件里 YFJ_Module_ + ucfirst('bazi_cesuan') 对应
class YFJ_Module_Bazi_cesuan extends YFJ_Base_Module {

    public function __construct() {
        $this->module_id = 'bazi_cesuan';             // 1. 修改模块 ID
        $this->shortcode = 'yfj_bazi_cesuan';         // 2. 修改短代码
        $this->api_endpoint = '/v1/Bazi/cesuan';      // 3. 配置对应的真实 API 接口

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
        $demo_json_zh_cn = '{"errcode":0,"errmsg":"请求成功","notice":"本次测算结果仅供娱乐使用，请勿用于封建迷信和违法用途。","data":{"base_info":{"sex":"坤造","name":"张三","gongli":"1988年1月8日12时20分","nongli":"丁卯年 十一月 十九日 午时","qiyun":"9年1月21天起运","jiaoyun":"1997年1月12日11时31分38秒","zhengge":"正官格","wuxing_xiji":"喜水 用金 闲木 仇火 忌土"},"bazi_info":{"kw":"子丑","tg_cg_god":["正财","劫财","日元","偏财"],"bazi":"丁卯 癸丑 壬戌 丙午","na_yin":"炉中火"},"chenggu":{"year_weight":"0.7","month_weight":"0.9","day_weight":"0.5","hour_weight":"1.0","total_weight":"3.1","description":"先贫后富近贵艺术衣食足用之人，忙忙碌碌苦中求，何日云开见日头，难得祖基家可立，中年衣食渐无忧。早年谋事艰苦，家道贫寒，為生计而苦苦挣扎，中年后才见顺利，兴家立业，衣食尚算无忧。"},"yinyuan":{"sanshishu_yinyuan":"夫妻天作之合，彼此连心，这段婚姻的成功源于双方对共同目标的坚持和对彼此的尊重，两人不仅是夫妻也是朋友，晚年两人是富裕的。"},"caiyun":{"sanshishu_caiyun":{"simple_desc":"孤寒敛财","detail_desc":"逐禄，金钱不缺，虽不是超级富豪，但也算一生财产充裕。缺点是喜欢追名逐利，不容易知足，而且平时又非常节省，储藏多多钱却不舍得花费，孤寒财主正是缘主的写照。"}},"sizhu":{"rizhu":"坐下财生杀，杀生印，杀印相生，主大贵，或武贵，但丁壬化木逢燥土，往往变成小人或坏人。壬戌日柱宜择丁卯日柱为配，巳火岁运中遇之，以属兔者佳。仁者以辅，礼者以配，炉中阴火，熬炼成丹，乃天下稀珍之求也！此种佳合，乃俊美崇尊之配也！相偕既久，相得亦欢！诚天成佳偶！"},"sx":"兔","xz":"摩羯座","mingyun":{"sanshishu_mingyun":"时逢天福是生时，定然仓库有盈余，宽宏大量根基稳，财帛光华百福齐。"},"xiyongshen":{"qiangruo":"八字偏弱","xiyongshen":"金，水","jishen":"土","xiyongshen_desc":"身弱，需补助，避免克泄耗，喜用金补水，避木忌土火。","jin_number":0,"mu_number":1,"shui_number":2,"huo_number":3,"tu_number":2,"zidang":1,"yidang":8,"zidang_percent":"11.11%","yidang_percent":"88.89%","tonglei":"金水","yilei":"木火土","rizhu_tiangan":"水","jin_score":12,"mu_score":30,"shui_score":81,"huo_score":95,"tu_score":46,"jin_score_percent":"4.55%","mu_score_percent":"11.36%","shui_score_percent":"30.68%","huo_score_percent":"35.98%","tu_score_percent":"17.42%","yinyang":"阴阳平衡"},"wuxing":{"detail_desc":"丁卯年生炉中火命","simple_desc":"火","simple_description":"从五行能量来看，主能量火气盛，主热忱之质天赋自带。缘主先天具感染力，行动力强，善恶分明，富正义感。火气偏盛易生急躁，需防冲动误事。次五行能量属水，后天运势主智变通达之境。缘主后天经世事沉浮后更善谋略，中年前后会形成独特思维体系。需防过度思虑生内耗，宜守本心定见。后天利于在变局中寻机，事业多靠洞察先机，晚年智识更厚，可享声望之福。","detail_description":"先天纳音炉中火聚温供暖，天性热忱包容。禀赋奉献之心，乐于助人，善团结他人，有强烈的集体归属感。行事积极主动，富有责任感，能为群体付出。易显急躁，操心过甚，不懂拒绝。天赋在于凝聚带动，然需防过度耗损。后天经细节打磨，添了精致严谨的特质。开阔豁达的本性不变，却减了粗疏失察，包容化为理解差异的智慧，大局观搭配细致心。待人真诚亦懂体察入微，重情义更兼周全，昔日疏懒变为开阔而不粗放。如大海浩瀚亦藏细流，既能保持包容天下的格局，也懂得关注细节，开阔而不粗疏，包容而能体察。"}}}';
        $demo_json_zh_tw = '{"errcode":0,"errmsg":"請求成功","notice":"本次測算結果僅供娛樂使用，請勿用於封建迷信和違法用途。","data":{"base_info":{"sex":"坤造","name":"張三","gongli":"1988年1月8日12時20分","nongli":"丁卯年 十一月 十九日 午時","qiyun":"9年1月21天起運","jiaoyun":"1997年1月12日11時31分38秒","zhengge":"正官格","wuxing_xiji":"喜水 用金 閑木 仇火 忌土"},"bazi_info":{"kw":"子丑","tg_cg_god":["正財","劫財","日元","偏財"],"bazi":"丁卯 癸丑 壬戌 丙午","na_yin":"爐中火"},"chenggu":{"year_weight":"0.7","month_weight":"0.9","day_weight":"0.5","hour_weight":"1.0","total_weight":"3.1","description":"先貧後富近貴藝術衣食足用之人，忙忙碌碌苦中求，何日雲開見日頭，難得祖基家可立，中年衣食漸無憂。早年謀事艱苦，家道貧寒，為生計而苦苦掙紮，中年後才見順利，興家立業，衣食尚算無憂。"},"yinyuan":{"sanshishu_yinyuan":"夫妻天作之合，彼此連心，這段婚姻的成功源於雙方對共同目標的堅持和對彼此的尊重，兩人不僅是夫妻也是朋友，晚年兩人是富裕的。"},"caiyun":{"sanshishu_caiyun":{"simple_desc":"孤寒斂財","detail_desc":"逐祿，金錢不缺，雖不是超級富豪，但也算一生財產充裕。缺點是喜歡追名逐利，不容易知足，而且平時又非常節省，儲藏多多錢卻不舍得花費，孤寒財主正是緣主的寫照。"}},"sizhu":{"rizhu":"坐下財生殺，殺生印，殺印相生，主大貴，或武貴，但丁壬化木逢燥土，往往變成小人或壞人。壬戌日柱宜擇丁卯日柱為配，巳火歲運中遇之，以屬兔者佳。仁者以輔，禮者以配，爐中陰火，熬煉成丹，乃天下稀珍之求也！此種佳合，乃俊美崇尊之配也！相偕既久，相得亦歡！誠天成佳偶！"},"sx":"兔","xz":"摩羯座","mingyun":{"sanshishu_mingyun":"時逢天福是生時，定然倉庫有盈余，寬宏大量根基穩，財帛光華百福齊。"},"xiyongshen":{"qiangruo":"八字偏弱","xiyongshen":"金，水","jishen":"土","xiyongshen_desc":"身弱，需補助，避免克泄耗，喜用金補水，避木忌土火。","jin_number":0,"mu_number":1,"shui_number":2,"huo_number":3,"tu_number":2,"zidang":1,"yidang":8,"zidang_percent":"11.11%","yidang_percent":"88.89%","tonglei":"金水","yilei":"木火土","rizhu_tiangan":"水","jin_score":12,"mu_score":30,"shui_score":81,"huo_score":95,"tu_score":46,"jin_score_percent":"4.55%","mu_score_percent":"11.36%","shui_score_percent":"30.68%","huo_score_percent":"35.98%","tu_score_percent":"17.42%","yinyang":"陰陽平衡"},"wuxing":{"detail_desc":"丁卯年生爐中火命","simple_desc":"火","simple_description":"從五行能量來看，主能量火氣盛，主熱忱之質天賦自帶。緣主先天具感染力，行動力強，善惡分明，富正義感。火氣偏盛易生急躁，需防沖動誤事。次五行能量屬水，後天運勢主智變通達之境。緣主後天經世事沈浮後更善謀略，中年前後會形成獨特思維體系。需防過度思慮生內耗，宜守本心定見。後天利於在變局中尋機，事業多靠洞察先機，晚年智識更厚，可享聲望之福。","detail_description":"先天納音爐中火聚溫供暖，天性熱忱包容。稟賦奉獻之心，樂於助人，善團結他人，有強烈的集體歸屬感。行事積極主動，富有責任感，能為群體付出。易顯急躁，操心過甚，不懂拒絕。天賦在於凝聚帶動，然需防過度耗損。後天經細節打磨，添了精致嚴謹的特質。開闊豁達的本性不變，卻減了粗疏失察，包容化為理解差異的智慧，大局觀搭配細致心。待人真誠亦懂體察入微，重情義更兼周全，昔日疏懶變為開闊而不粗放。如大海浩瀚亦藏細流，既能保持包容天下的格局，也懂得關註細節，開闊而不粗疏，包容而能體察。"}}}';
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

        // 5. 返回处理好的干净数据给 process_api_data
        return $data;
    }
}