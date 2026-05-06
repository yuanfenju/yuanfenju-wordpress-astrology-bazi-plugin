<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// 注意这里的类名，要和主文件里 YFJ_Module_ + ucfirst('bazi_cesuan') 对应
class YFJ_Module_Liupan_daliuren extends YFJ_Base_Module {

    public function __construct() {
        $this->module_id = 'liupan_daliuren';             // 1. 修改模块 ID
        $this->shortcode = 'yfj_liupan_daliuren';         // 2. 修改短代码
        $this->api_endpoint = '/v1/Liupan/daliuren';      // 3. 配置对应的真实 API 接口

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
        $demo_json_zh_cn = '{"errcode":0,"errmsg":"请求成功","notice":"本次测算结果仅供娱乐使用，请勿用于封建迷信和违法用途。","data":{"name":"测试数据","sex":"坤造","gongli":"2025年03月15日15时12分","nongli":"2025年二月十六日申时","jieqi":"2025年02月18日18时06分雨水","sizhu_info":{"year_gan":"乙","year_zhi":"巳","month_gan":"己","month_zhi":"卯","day_gan":"癸","day_zhi":"未","hour_gan":"庚","hour_zhi":"申"},"xunkong_info":{"year_xunkong":"寅卯","month_xunkong":"申酉","day_xunkong":"申酉","hour_xunkong":"子丑"},"yuejiang":"亥将","hangnian":"--","nianming":"--","gong_pan":[{"shenpan":"玄武","tianpan":"寅","dipan":"亥宫"},{"shenpan":"太阴","tianpan":"卯","dipan":"子宫"},{"shenpan":"天后","tianpan":"辰","dipan":"丑宫"},{"shenpan":"贵人","tianpan":"巳","dipan":"寅宫"},{"shenpan":"螣蛇","tianpan":"午","dipan":"卯宫"},{"shenpan":"朱雀","tianpan":"未","dipan":"辰宫"},{"shenpan":"六合‌","tianpan":"申","dipan":"巳宫"},{"shenpan":"勾陈","tianpan":"酉","dipan":"午宫"},{"shenpan":"青龙","tianpan":"戌","dipan":"未宫"},{"shenpan":"天空","tianpan":"亥","dipan":"申宫"},{"shenpan":"白虎","tianpan":"子","dipan":"酉宫"},{"shenpan":"太常","tianpan":"丑","dipan":"戌宫"}],"sike_info":[{"guishen":"太常","sike":"丑","xunke":"戌"},{"guishen":"青龙","sike":"戌","xunke":"未"},{"guishen":"朱雀","sike":"未","xunke":"辰"},{"guishen":"天后","sike":"辰","xunke":"癸"}],"sanchuan_info":[{"sanchuan_biaoshi":"初传","sanchuan_liuqin":"官鬼","sanchuan_ganzhi":"庚辰","sanchuan_guishen":"天后"},{"sanchuan_biaoshi":"中传","sanchuan_liuqin":"官鬼","sanchuan_ganzhi":"癸未","sanchuan_guishen":"朱雀"},{"sanchuan_biaoshi":"末传","sanchuan_liuqin":"官鬼","sanchuan_ganzhi":"甲戌","sanchuan_guishen":"青龙"}],"daliuren_desc":{"keti":"元首，斩关，稼穑。","keyi":"众情皆恶，宜自相度，闭口居中，免被凌虐。","jieyue":"三传和四课中，没有一处不是代表鬼煞的存在，并且还重重地遇上了魁罡，这是极为凶险、恶劣的征兆。此时应该深入思考、周全谋划，根据时机灵活行动，这样才能够避免灾祸。要是不经过仔细考量就贸然行事，必然会遭受祸患。中传呈现闭口之象，这就要求必须谨慎言语，如此或许可以避免遭受欺凌和虐待。","duanyue":"墓神覆盖在日干之上，意味着事情处于昏暗不明的状态，大凡占问的事情都免不了会遇到阻滞。夜晚白虎降临在家宅且克制日干，这是极其惊恐、危险的情况。不过值得庆幸的是，凶神之间相互冲战，这能够使大事化小，这也算是一种以凶制凶的办法。","tianqi":"墓神覆日，预示着占问天气时不会晴朗。课传之中全都是土象，表明天气会一直处于阴沉状态，不会降雨。土性干燥，缺乏水汽的流通，难以形成降雨的条件，天空将持续阴沉，给人压抑之感。","moushi":"此课象显示，谋事过程充满艰难险阻。三传四课皆为鬼煞，且遇魁罡，环境极为恶劣。在谋划时，需深思熟虑，谨慎行事，不可贸然行动。任何疏忽都可能导致灾祸降临。同时，要谨言慎行，避免因言语不当引发麻烦，只有这样，才有可能在困境中寻得一线生机，推动事情发展。","jiazhai":"白天占得此卦，情况尚可。但若是夜晚占得，就极为凶险了。夜晚白虎临宅克干，可能会给家中带来意外灾祸，如家人突发疾病、财物受损等。家中成员需提高警惕，注意安全防范。若白天占得，虽无大凶，但也需留意家中潜在的问题，及时解决，以维护家庭的安宁。","hunyin":"从课象来看，男方性格可能不够温和中正，女方性格也不够柔顺，双方结合存在一定困难。在相处过程中，容易因性格差异产生矛盾，难以和谐相处，因此这段婚姻不太容易成功，双方需谨慎考虑彼此关系。","jibing":"课象呈现土克水之象，表明疾病可能与肾、泌尿系统等相关。不过，如果年命中临有木神，木可克土，缓解土对水的克制，那么病情或许还有得救。患者需及时就医，根据医生建议进行治疗，同时可借助一些与木相关的调养方法，如佩戴木质饰品、选择与木相关的环境调养等。","huaiyun":"如果是白天占得，生产过程会比较顺利；若是夜晚占得，可能会有担忧和惊吓。在古代占筮理论中，白天阳气盛，利于生产；夜晚阴气重，可能会出现一些意外情况，如难产、孕妇情绪紧张等。孕妇及其家人需提前做好准备，应对可能出现的状况。","qiucai":"课象中仅有一点丁财，但由于整体环境凶险，这点财恐怕也不敢轻易求取。在求财过程中，会面临诸多风险和阻碍，如投资失利、合作纠纷等，稍有不慎就可能遭受损失，所以在当前阶段，不宜贸然进行求财活动。","xunren":"从课象来看，寻人难度较大。三传四课的凶险之象，使得寻找过程充满阻碍。被寻找的人可能处于危险或隐蔽的环境中，难以找寻。且墓神覆日带来的昏暗不明，也让寻找线索变得模糊不清，需要花费大量精力和时间去排查。","shiwu":"失物找回的可能性较小。课象中的凶险和阻滞，就像失物陷入了一个难以触及的困境。可能失物已经被转移到了很远或者非常隐蔽的地方，按照常规的寻找方法，很难找到失物的下落，找回失物的希望较为渺茫。","xingren":"天罡加季，这表明在外的行人马上就会到达。他们可能已经在归途，并且距离目的地不远。等待行人归来的人可以做好迎接的准备，很快就能与行人相见。","chuxing":"课名为游子，意味着出行者不会安居家中，会有出行的动向。然而，出行过程可能不会一帆风顺，会遇到各种阻碍，如道路不通、天气不佳等。出行前需做好充分准备，应对可能出现的突发情况，以确保出行顺利。"}}}';
        $demo_json_zh_tw = '{"errcode":0,"errmsg":"请求成功","notice":"本次测算结果仅供娱乐使用，请勿用于封建迷信和违法用途。","data":{"name":"測試數據","sex":"坤造","gongli":"2025年03月15日15時12分","nongli":"2025年二月十六日申時","jieqi":"2025年02月18日18時06分雨水","sizhu_info":{"year_gan":"乙","year_zhi":"巳","month_gan":"己","month_zhi":"卯","day_gan":"癸","day_zhi":"未","hour_gan":"庚","hour_zhi":"申"},"xunkong_info":{"year_xunkong":"寅卯","month_xunkong":"申酉","day_xunkong":"申酉","hour_xunkong":"子丑"},"yuejiang":"亥將","hangnian":"--","nianming":"--","gong_pan":[{"shenpan":"玄武","tianpan":"寅","dipan":"亥宮"},{"shenpan":"太陰","tianpan":"卯","dipan":"子宮"},{"shenpan":"天後","tianpan":"辰","dipan":"丑宮"},{"shenpan":"貴人","tianpan":"巳","dipan":"寅宮"},{"shenpan":"螣蛇","tianpan":"午","dipan":"卯宮"},{"shenpan":"朱雀","tianpan":"未","dipan":"辰宮"},{"shenpan":"六合‌","tianpan":"申","dipan":"巳宮"},{"shenpan":"勾陳","tianpan":"酉","dipan":"午宮"},{"shenpan":"青龍","tianpan":"戌","dipan":"未宮"},{"shenpan":"天空","tianpan":"亥","dipan":"申宮"},{"shenpan":"白虎","tianpan":"子","dipan":"酉宮"},{"shenpan":"太常","tianpan":"丑","dipan":"戌宮"}],"sike_info":[{"guishen":"太常","sike":"丑","xunke":"戌"},{"guishen":"青龍","sike":"戌","xunke":"未"},{"guishen":"朱雀","sike":"未","xunke":"辰"},{"guishen":"天後","sike":"辰","xunke":"癸"}],"sanchuan_info":[{"sanchuan_biaoshi":"初傳","sanchuan_liuqin":"官鬼","sanchuan_ganzhi":"庚辰","sanchuan_guishen":"天後"},{"sanchuan_biaoshi":"中傳","sanchuan_liuqin":"官鬼","sanchuan_ganzhi":"癸未","sanchuan_guishen":"朱雀"},{"sanchuan_biaoshi":"末傳","sanchuan_liuqin":"官鬼","sanchuan_ganzhi":"甲戌","sanchuan_guishen":"青龍"}],"daliuren_desc":{"keti":"元首，斬關，稼穡。","keyi":"眾情皆惡，宜自相度，閉口居中，免被淩虐。","jieyue":"三傳和四課中，沒有一處不是代表鬼煞的存在，並且還重重地遇上了魁罡，這是極為兇險、惡劣的征兆。此時應該深入思考、周全謀劃，根據時機靈活行動，這樣才能夠避免災禍。要是不經過仔細考量就貿然行事，必然會遭受禍患。中傳呈現閉口之象，這就要求必須謹慎言語，如此或許可以避免遭受欺淩和虐待。","duanyue":"墓神覆蓋在日幹之上，意味著事情處於昏暗不明的狀態，大凡占問的事情都免不了會遇到阻滯。夜晚白虎降臨在家宅且剋製日幹，這是極其驚恐、危險的情況。不過值得慶幸的是，兇神之間相互沖戰，這能夠使大事化小，這也算是一種以兇製兇的辦法。","tianqi":"墓神覆日，預示著占問天氣時不會晴朗。課傳之中全都是土象，表明天氣會一直處於陰沈狀態，不會降雨。土性幹燥，缺乏水汽的流通，難以形成降雨的條件，天空將持續陰沈，給人壓抑之感。","moushi":"此課象顯示，謀事過程充滿艱難險阻。三傳四課皆為鬼煞，且遇魁罡，環境極為惡劣。在謀劃時，需深思熟慮，謹慎行事，不可貿然行動。任何疏忽都可能導致災禍降臨。同時，要謹言慎行，避免因言語不當引發麻煩，只有這樣，才有可能在困境中尋得一線生機，推動事情發展。","jiazhai":"白天占得此卦，情況尚可。但若是夜晚占得，就極為兇險了。夜晚白虎臨宅剋幹，可能會給家中帶來意外災禍，如家人突發疾病、財物受損等。家中成員需提高警惕，註意安全防範。若白天占得，雖無大兇，但也需留意家中潛在的問題，及時解決，以維護家庭的安寧。","hunyin":"從課象來看，男方性格可能不夠溫和中正，女方性格也不夠柔順，雙方結合存在一定困難。在相處過程中，容易因性格差異產生矛盾，難以和諧相處，因此這段婚姻不太容易成功，雙方需謹慎考慮彼此關系。","jibing":"課象呈現土剋水之象，表明疾病可能與腎、泌尿系統等相關。不過，如果年命中臨有木神，木可剋土，緩解土對水的剋製，那麽病情或許還有得救。患者需及時就醫，根據醫生建議進行治療，同時可借助一些與木相關的調養方法，如佩戴木質飾品、選擇與木相關的環境調養等。","huaiyun":"如果是白天占得，生產過程會比較順利；若是夜晚占得，可能會有擔憂和驚嚇。在古代占筮理論中，白天陽氣盛，利於生產；夜晚陰氣重，可能會出現一些意外情況，如難產、孕婦情緒緊張等。孕婦及其家人需提前做好準備，應對可能出現的狀況。","qiucai":"課象中僅有一點丁財，但由於整體環境兇險，這點財恐怕也不敢輕易求取。在求財過程中，會面臨諸多風險和阻礙，如投資失利、合作糾紛等，稍有不慎就可能遭受損失，所以在當前階段，不宜貿然進行求財活動。","xunren":"從課象來看，尋人難度較大。三傳四課的兇險之象，使得尋找過程充滿阻礙。被尋找的人可能處於危險或隱蔽的環境中，難以找尋。且墓神覆日帶來的昏暗不明，也讓尋找線索變得模糊不清，需要花費大量精力和時間去排查。","shiwu":"失物找回的可能性較小。課象中的兇險和阻滯，就像失物陷入了一個難以觸及的困境。可能失物已經被轉移到了很遠或者非常隱蔽的地方，按照常規的尋找方法，很難找到失物的下落，找回失物的希望較為渺茫。","xingren":"天罡加季，這表明在外的行人馬上就會到達。他們可能已經在歸途，並且距離目的地不遠。等待行人歸來的人可以做好迎接的準備，很快就能與行人相見。","chuxing":"課名為遊子，意味著出行者不會安居家中，會有出行的動向。然而，出行過程可能不會一帆風順，會遇到各種阻礙，如道路不通、天氣不佳等。出行前需做好充分準備，應對可能出現的突發情況，以確保出行順利。"}}}';

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