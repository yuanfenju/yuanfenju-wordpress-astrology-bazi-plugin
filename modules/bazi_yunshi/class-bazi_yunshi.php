<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// 注意这里的类名，要和主文件里 YFJ_Module_ + ucfirst('bazi_cesuan') 对应
class YFJ_Module_Bazi_yunshi extends YFJ_Base_Module {

    public function __construct() {
        $this->module_id = 'bazi_yunshi';             // 1. 修改模块 ID
        $this->shortcode = 'yfj_bazi_yunshi';         // 2. 修改短代码
        $this->api_endpoint = '/v1/Bazi/yunshi';      // 3. 配置对应的真实 API 接口

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
        $demo_json_zh_cn = '{"errcode":0,"errmsg":"请求成功","notice":"本次测算结果仅供娱乐使用，请勿用于封建迷信和违法用途。","data":{"base_info":{"sex":"坤造","name":0,"gongli":"2005-12-23 12:05:00","nongli":"二〇〇五年冬月廿三日 午时","yeargz":"乙酉","monthgz":"戊子","daygz":"辛巳","hourgz":"甲午","shengxiao":"鸡","zhengge":"食神格","xiyongshen":{"qiangruo":"八字偏弱","xiyongshen":"土，金","jishen":"火","xiyongshen_desc":"身弱，需补助，避免克泄耗，喜用土补金，避水忌火木。","jin_number":2,"mu_number":2,"shui_number":1,"huo_number":2,"tu_number":1,"zidang":2,"yidang":7,"zidang_percent":"22.22%","yidang_percent":"77.78%","tonglei":"土金","yilei":"木水火","rizhu_tiangan":"金","jin_score":73,"mu_score":72,"shui_score":30,"huo_score":36,"tu_score":53,"jin_score_percent":"27.65%","mu_score_percent":"27.27%","shui_score_percent":"11.36%","huo_score_percent":"13.64%","tu_score_percent":"20.08%","yinyang":"阴阳平衡"},"wuxing_xiji":"喜金 用土 闲水 仇木 忌火"},"yunshi_info":{"lucky_number":"2、7","lucky_color":"赤色、红色","lucky_accessory":"火焰纹袖扣、红珊瑚项链、玫红包链","lucky_foods":"羊肉、红糖、桂圆、樱桃酱、红豆沙","lucky_directions":"南方","lucky_yi":"造屋、合脊、修门","lucky_ji":"行丧、书法、经络","health_score":62,"career_score":57,"love_score":82,"wealth_score":52,"fortune_score":63,"jixiong_today":"中凶","health_description":"今日的健康运势挑战升级，可能面临健康危机。身体可能因长时间使用电子设备出现视力下降，需遵循20-20-20法则，保持正确用眼姿势，多吃护眼食物，定期进行眼部检查。","career_description":"今日的事业运势略显压力，团队协作存在隐患。跨部门项目中可能因职责划分不清产生摩擦，需主动与上级沟通明确任务边界，避免陷入无意义消耗，维护工作效率。","love_description":"今日爱情运势在亲密关系中存在认知偏差，单身者可能因外界评价动摇对心仪对象的判断，他人的看法与你的真实感受产生矛盾，需信任自己的直觉而非盲从建议。恋爱中的人易将伴侣的独立行为解读为疏离，对方的独处需求被误判为情感冷却，需明确独立空间与情感连接并非对立关系。已婚者要警惕比较心理对关系的影响，将自家生活与他人对比易产生不满，专注于两人共同的成长轨迹更有意义。","wealth_description":"今日的财富运势略显波折，暗藏挑战。财务状况容易受到市场波动、政策变化等外部因素影响，可能会面临资金紧张的局面。此时，需严格把控每一笔开支，削减不必要的消费项目，优先偿还高利息债务。","fortune_description":"今日的运势提示，你将迎来需全神贯注应对挑战的一天。工作中可能遭遇资源短缺或竞争压力，如客户流失或方案被否。需沉下心分析细节，在专业领域多做备份预案，凭借坚韧态度逐个攻克难关，避免因焦虑导致失误。"}}}';
        $demo_json_zh_tw = '{"errcode":0,"errmsg":"请求成功","notice":"本次测算结果仅供娱乐使用，请勿用于封建迷信和违法用途。","data":{"base_info":{"sex":"坤造","name":0,"gongli":"2005-12-23 12:05:00","nongli":"二〇〇五年冬月廿三日 午時","yeargz":"乙酉","monthgz":"戊子","daygz":"辛巳","hourgz":"甲午","shengxiao":"雞","zhengge":"食神格","xiyongshen":{"qiangruo":"八字偏弱","xiyongshen":"土，金","jishen":"火","xiyongshen_desc":"身弱，需補助，避免剋泄耗，喜用土補金，避水忌火木。","jin_number":2,"mu_number":2,"shui_number":1,"huo_number":2,"tu_number":1,"zidang":2,"yidang":7,"zidang_percent":"22.22%","yidang_percent":"77.78%","tonglei":"土金","yilei":"木水火","rizhu_tiangan":"金","jin_score":73,"mu_score":72,"shui_score":30,"huo_score":36,"tu_score":53,"jin_score_percent":"27.65%","mu_score_percent":"27.27%","shui_score_percent":"11.36%","huo_score_percent":"13.64%","tu_score_percent":"20.08%","yinyang":"陰陽平衡"},"wuxing_xiji":"喜金 用土 閑水 仇木 忌火"},"yunshi_info":{"lucky_number":"2、7","lucky_color":"赤色、紅色","lucky_accessory":"火焰紋袖扣、紅珊瑚項鏈、玫紅包鏈","lucky_foods":"羊肉、紅糖、桂圓、櫻桃醬、紅豆沙","lucky_directions":"南方","lucky_yi":"造屋、合脊、修門","lucky_ji":"行喪、書法、經絡","health_score":62,"career_score":57,"love_score":82,"wealth_score":52,"fortune_score":63,"jixiong_today":"中兇","health_description":"今日的健康運勢挑戰升級，可能面臨健康危機。身體可能因長時間使用電子設備出現視力下降，需遵循20-20-20法則，保持正確用眼姿勢，多吃護眼食物，定期進行眼部檢查。","career_description":"今日的事業運勢略顯壓力，團隊協作存在隱患。跨部門項目中可能因職責劃分不清產生摩擦，需主動與上級溝通明確任務邊界，避免陷入無意義消耗，維護工作效率。","love_description":"今日愛情運勢在親密關系中存在認知偏差，單身者可能因外界評價動搖對心儀對象的判斷，他人的看法與你的真實感受產生矛盾，需信任自己的直覺而非盲從建議。戀愛中的人易將伴侶的獨立行為解讀為疏離，對方的獨處需求被誤判為情感冷卻，需明確獨立空間與情感連接並非對立關系。已婚者要警惕比較心理對關系的影響，將自家生活與他人對比易產生不滿，專註於兩人共同的成長軌跡更有意義。","wealth_description":"今日的財富運勢略顯波折，暗藏挑戰。財務狀況容易受到市場波動、政策變化等外部因素影響，可能會面臨資金緊張的局面。此時，需嚴格把控每一筆開支，削減不必要的消費項目，優先償還高利息債務。","fortune_description":"今日的運勢提示，你將迎來需全神貫註應對挑戰的一天。工作中可能遭遇資源短缺或競爭壓力，如客戶流失或方案被否。需沈下心分析細節，在專業領域多做備份預案，憑借堅韌態度逐個攻剋難關，避免因焦慮導致失誤。"}}}';
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