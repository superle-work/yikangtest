$(function(){
    /**
     * 初始化
     */
    function init(){
        bindEvent();
    }

    /**
     * 绑定事件
     */
    function bindEvent(){
        /**
         * 添加
         */
        $(".send").click(function(){
            window.location.href = "./index.php?c=store&a=checkMember";
        });
    }
    init();
});