   /***
     * 判断日期的格式是否正确
     */
jQuery.validator.addMethod("isDate", function(value, element){
	var ereg = /^(\d{1,4})(-|\/)(\d{1,2})(-|\/)(\d{1,2})$/;
	var r = value.match(ereg);
	if (r == null) {
		return false;
	}
	var d = new Date(r[1], r[3] - 1, r[5]);
	var result = (d.getFullYear() == r[1] && (d.getMonth() + 1) == r[3] && d.getDate() == r[5]);
	return this.optional(element) || (result);
	}, "请输入正确的日期");

/**
 *判断用户名，是否为字母、数字和“_”组成，且以英文字母开头
 */
jQuery.validator.addMethod("username", function(value, element){
	var ereg = /^[a-zA-Z][a-zA-Z0-9_]*$/;
	return ereg.test(value);
}, "用户名由字母和数字组成");
/**
 *判断密码，是否为字母数字、_、.组成
 */
jQuery.validator.addMethod("password", function(value, element){
    var ereg = /^[a-zA-Z0-9][\w\.]+$/;
    return ereg.test(value);
}, "密码由字母、数字、_、.组成");

/**
  *确认密码
  */
jQuery.validator.addMethod("confirmPwd", function(value, element){
    var confPwd=$(element).val();
    var pwd = $(element).parent().parent().prev().find("#password").val();
   return confPwd == pwd;
}, "输入错误");
/**
 *判断昵称，是否为字母、数字、汉字组成
 */
jQuery.validator.addMethod("nickname", function(value, element){
    var ereg = /^[\u4e00-\u9fa5a-zA-Z0-9-\s]+$/;
    if(value!=undefined && value!=""){
        return ereg.test(value);
    }else{//为空时不验证
        return true;
    }
}, "不能含有特殊字符");
/**
 *匹配身份证,此处方法名不能叫“ID”，否则会产生问题
 */
jQuery.validator.addMethod("PID",function(value,element){
    var reg=/d{15}|d{18}/;
})
/**
 *判断商品价格，是否为数字组成,首位不为零,总位数不超过7
 */
jQuery.validator.addMethod("goodsPrice", function(value, element){
    var ereg = /^[1-9][0-9]{0,7}$|^0$|^([0-9]|[1-9][0-9]{0,7}).[0-9]{1,2}$/;
    return ereg.test(value);
}, "价格由数字组成,最多包含两位小数");
/**
*判断商品名称是否合法
*/
jQuery.validator.addMethod("goodsName", function(value, element){
   var ereg = /^[\u4e00-\u9fa5a-zA-Z0-9-\s]+$/;
   return ereg.test(value);
}, "不能含有特殊字符");
/**
 * 判断关键字是否由中文、英文字母、数字组成
 */
jQuery.validator.addMethod("keywords",function(value,element){
	var ereg = /^[\u4e00-\u9fa5a-zA-Z0-9-\s]+$/;
	return ereg.test(value);
},"不能含有特殊字符");

/**
 * 判断结束日期是否不小于开始时间
 */
jQuery.validator.addMethod("isEndDateValid",function(value,element){
	var start_date = $(element).parents("tr").prev().find("input").val();
	return start_date <= value;
},"结束时间必需不小于开始日期");

/**
 * 判断结束时间是否不小于开始时间
 */
jQuery.validator.addMethod("isEndTimeValid",function(value,element){
	var start_time = $(element).parents("tr").prev().find("#start_time").val();
	return start_time <= value;
},"结束时间必需不小于开始时间");

/**
 * 判断生效日期是否有效，即是否不小于当前时间
 */
jQuery.validator.addMethod("isEffectiveDateValid",function(value,element){
	var effectiveDate=$(element).val();
	var currentDate=new Date();
	var month=currentDate.getMonth()+1;
	var day=currentDate.getDate();
    var h=currentDate.getHours();
    var m=currentDate.getMinutes();
    var s=currentDate.getSeconds();
	if(month<10){
    	month="0"+month;
    }
    if(day<10){
    	day="0"+day;
    }
    if(h<10){
    	h="0"+h;
    }
    if(m<10){
    	m="0"+m;
    }
    if(s<0){
    	s="0"+s;
    }
var date=currentDate.getFullYear()+"-"+month+"-"+day+" "+h+":"+m+":"+s;
	//alert("current:"+date+" effec:"+effectiveDate);
	if(effectiveDate < date){
		return false;
	}
	return true;
});
/**
 *判断商品编号，是否为数字组成且位数为18位
 */
jQuery.validator.addMethod("goodsNo", function(value, element){
	var ereg = /^[0-9][0-9]{0,17}$/;
	return ereg.test(value);
}, "编号必须由数字组成");
/**
 *判断电话号码格式是否正确
 */
jQuery.validator.addMethod("phone", function(value, element){
	var ereg = /^(\d|-)*$/
	return ereg.test(value);
}, "电话号码格式不正确");

/**
 *判断电话号码格式是否正确
 */
jQuery.validator.addMethod("integer", function(value, element){
	var ereg = /^[0-9]*[1-9][0-9]*$/
	return ereg.test(value);
}, "必须为正整数");