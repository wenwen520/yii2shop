var zTreeObj;
// zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
var setting = {
    data: {
        simpleData: {
            enable: true,
            idKey: "id",
            pIdKey: "parent_id",
            rootPId: 0
        }
    }
};


// zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
var zNodes =[{"id":3,"tree":3,"lft":1,"rgt":6,"depth":0,"name":"机票/酒店/旅游/生活","parent_id":0,"intro":"说走就走的旅行！"},{"id":4,"tree":4,"lft":1,"rgt":6,"depth":0,"name":"美妆个护/宠物","parent_id":0,"intro":"忠犬八公"},{"id":5,"tree":5,"lft":1,"rgt":6,"depth":0,"name":"图书/音像/电子书","parent_id":0,"intro":"燥起来"},{"id":6,"tree":5,"lft":4,"rgt":5,"depth":1,"name":"文艺","parent_id":5,"intro":"文艺青年"},{"id":7,"tree":5,"lft":2,"rgt":3,"depth":1,"name":"生活","parent_id":5,"intro":"生活总是多姿多彩"},{"id":8,"tree":4,"lft":4,"rgt":5,"depth":1,"name":"宠物生活","parent_id":4,"intro":"宠物也有生活"},{"id":9,"tree":4,"lft":2,"rgt":3,"depth":1,"name":"香水彩妆","parent_id":4,"intro":"香奈儿香水"},{"id":10,"tree":3,"lft":4,"rgt":5,"depth":1,"name":"海外生活","parent_id":3,"intro":"海龟"},{"id":11,"tree":3,"lft":2,"rgt":3,"depth":1,"name":"游戏","parent_id":3,"intro":"电子竞技"}];
$(document).ready(function(){
    zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
});/**
 * Created by stefan on 2017/6/11.
 */
