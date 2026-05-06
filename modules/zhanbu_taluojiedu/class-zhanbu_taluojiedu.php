<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// 注意这里的类名，要和主文件里 YFJ_Module_ + ucfirst('bazi_cesuan') 对应
class YFJ_Module_Zhanbu_taluojiedu extends YFJ_Base_Module {

    public function __construct() {
        $this->module_id = 'zhanbu_taluojiedu';             // 1. 修改模块 ID
        $this->shortcode = 'yfj_zhanbu_taluojiedu';         // 2. 修改短代码
        $this->api_endpoint = '/v1/Zhanbu/taluojiedu';      // 3. 配置对应的真实 API 接口

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

        $demo_json_zh_cn = '{"errcode":0,"errmsg":"请求成功","notice":"本次测算结果仅供娱乐使用，请勿用于封建迷信和违法用途。","data":{"cards":[{"positions_index":1,"positions_name":"核心信息","positions_desc":"当前问题最直接的能量与指向","orientation_code":1,"orientation_text":"正位","card_no":8,"card_name":"战车","card_keywords":"意志、掌控、行动力、征服、目标导向、内在平衡、对抗、驾驭、失控、阻碍、坚韧、冲动、自律、冲突化解、自我突破、权力、焦躁、进退失据、情绪驾驭、外在强势、内在脆弱、征途、胜负欲、方向感、混乱、理性驱动、情感裹挟","card_astrology":"巨蟹座","card_element":"水","card_description":"战车牌铺展于破晓的征途中，青铜铸就的战车周身纹饰鎏金，车轮深镌「掌控即平衡」的古符文，双狮（一白一黑，象征内在二元对立）昂首牵引，鬃毛如烈焰翻涌，爪下荒原生满坚韧的芨芨草。驾驶者身着嵌巨蟹星纹的赤红战甲，头盔垂珍珠帘幕遮半张脸，左手执刻「意志」铭文的青铜权杖牢牢掌控缰绳，右手按心口嵌月长石的护心镜（呼应巨蟹座的月相能量），显外在强势却内守情绪的平衡。战车碾过的路径向晨雾深处延伸，远处矗立刻「征途而非征服」的界碑，穹顶巨蟹座星群如碎银洒落，战甲边缘缠绕的青藤（象征韧性）随疾风舒展。整幅画面无怯懦的退缩，却藏「驾驭即胜利」的内核——胜利不是碾压外在对手，而是驯服内在的情绪冲突，以意志锚定方向，以水元素的柔软平衡火元素的锋芒，是内在秩序与外在行动力的共生。","card_interpretation":{"general":"金的纯粹与水的柔韧相融，内心如鎏金的权杖，意志带着金的坚定与水的情绪平衡，行动力锚定清晰目标，驾驭内外冲突如控缰驭狮。胜利在金的规则里落地，藏「理性即掌控」的神秘笃定，是意志坚定与情绪平衡的完美契合。","topic":"情感如纯金般澄澈，关系中以理性掌控节奏，平衡情感需求与现实目标，行动力指向长久的稳定联结。不沉溺情绪内耗，也不忽视彼此感受，藏「平衡即长久」的深层期待，强势却有温柔的边界。","advice":"保持情感的理性掌控与情绪平衡，不必用强势绑架彼此，相信「平衡即联结」的韵律，让关系在掌控与温柔的平衡中生长，不僵化也不松散。"},"image_id":8,"image_url":"https://yuanfenju.com/Public/img/taluojiedu/upright/8.jpg"}],"overall_interpretation":{"summary_message":"你目前的处境处于「意志推进」，这张牌代表你目前所面对的核心问题与主题，整体能量属于「正向推进，整体能量顺畅，事情具备向前发展的条件。」","oracle_message":"婚姻的本质，是两个人一起成为更好的人。"},"environment":{"calculation_time":"2026-05-03 17:04:11","time_ganzhi":"己酉","time_element":"金局"}}}';
        $demo_json_zh_tw = '{"errcode":0,"errmsg":"請求成功","notice":"本次測算結果僅供娛樂使用，請勿用於封建迷信和違法用途。","data":{"cards":[{"positions_index":1,"positions_name":"核心信息","positions_desc":"當前問題最直接的能量與指向","orientation_code":1,"orientation_text":"正位","card_no":8,"card_name":"戰車","card_keywords":"意誌、掌控、行動力、征服、目標導向、內在平衡、對抗、駕馭、失控、阻礙、堅韌、沖動、自律、沖突化解、自我突破、權力、焦躁、進退失據、情緒駕馭、外在強勢、內在脆弱、征途、勝負欲、方向感、混亂、理性驅動、情感裹挾","card_astrology":"巨蟹座","card_element":"水","card_description":"戰車牌鋪展於破曉的征途中，青銅鑄就的戰車周身紋飾鎏金，車輪深鐫「掌控即平衡」的古符文，雙獅（一白一黑，象征內在二元對立）昂首牽引，鬃毛如烈焰翻湧，爪下荒原生滿堅韌的芨芨草。駕駛者身著嵌巨蟹星紋的赤紅戰甲，頭盔垂珍珠簾幕遮半張臉，左手執刻「意誌」銘文的青銅權杖牢牢掌控韁繩，右手按心口嵌月長石的護心鏡（呼應巨蟹座的月相能量），顯外在強勢卻內守情緒的平衡。戰車碾過的路徑向晨霧深處延伸，遠處矗立刻「征途而非征服」的界碑，穹頂巨蟹座星群如碎銀灑落，戰甲邊緣纏繞的青藤（象征韌性）隨疾風舒展。整幅畫面無怯懦的退縮，卻藏「駕馭即勝利」的內核——勝利不是碾壓外在對手，而是馴服內在的情緒沖突，以意誌錨定方向，以水元素的柔軟平衡火元素的鋒芒，是內在秩序與外在行動力的共生。","card_interpretation":{"general":"金的純粹與水的柔韌相融，內心如鎏金的權杖，意誌帶著金的堅定與水的情緒平衡，行動力錨定清晰目標，駕馭內外沖突如控韁馭獅。勝利在金的規則裏落地，藏「理性即掌控」的神秘篤定，是意誌堅定與情緒平衡的完美契合。","topic":"情感如純金般澄澈，關系中以理性掌控節奏，平衡情感需求與現實目標，行動力指向長久的穩定聯結。不沈溺情緒內耗，也不忽視彼此感受，藏「平衡即長久」的深層期待，強勢卻有溫柔的邊界。","advice":"保持情感的理性掌控與情緒平衡，不必用強勢綁架彼此，相信「平衡即聯結」的韻律，讓關系在掌控與溫柔的平衡中生長，不僵化也不松散。"},"image_id":8,"image_url":"https://yuanfenju.com/Public/img/taluojiedu/upright/8.jpg"}],"overall_interpretation":{"summary_message":"你目前的處境處於「意誌推進」，這張牌代表你目前所面對的核心問題與主題，整體能量屬於「正向推進，整體能量順暢，事情具備向前發展的條件。」","oracle_message":"婚姻的本質，是兩個人一起成為更好的人。"},"environment":{"calculation_time":"2026-05-03 17:04:11","time_ganzhi":"己酉","time_element":"金局"}}}';


        if ($lang == 'zh-tw') {
            $demo_json = $demo_json_zh_tw;
        } else {
            $demo_json = $demo_json_zh_cn;
        }

        // 2. 解析为 PHP 数组
        $demo_array_json = json_decode($demo_json, true);

        // 3. 【核心提取】剥离外层的 errcode 状态，只提取 data 节点内容
        $data = $demo_array_json['data'] ?? [];

        // 5. 返回处理好的干净数据给 process_api_data
        return $data;
    }
}