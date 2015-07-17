$(function(){
	//index---------------------
	//检测登录有没有输入邮箱和密码
	$("#login").click(function(event){
		var psw=$("#psw").prop("value");//这里似乎好像必须用prop不能用attr，可能是因为在html里没有设置过value，所以attr不能用
		var mail=$("#mail").prop("value");
		if(mail===""||psw===""){
			alert("请输入邮箱或者密码");
			event.preventDefault();
		}
	})

	//光标移动时检测用户名是否合法
	$("#rname").blur(function(event){
		var rname=$("#rname").prop("value");
		nameValidateblur(rname);
	})
    
	//点击注册时，检测注册信息是否完整和合法
	$("#register").click(function(event){
		event.preventDefault();
		var rname=$("#rname").prop("value");
		var remail=$("#remail").prop("value");

		var reg = new RegExp('^[a-z0-9]+([._\\-]*[a-z0-9])*@([a-z0-9]+[-a-z0-9]*[a-z0-9]+.){1,63}[a-z0-9]+$');
		if(!remail.match(reg)){
			document.getElementById("emailvalid").innerHTML="请输入正确的邮箱";
			$("#emailvalid").show(300);
			return false;
		}
		else{
			$("#emailvalid").hide(300);
            $("#registerform").submit();
		}
		nameValidateblur(rname);
	})

//上传界面！！！！---------------------
	//上传
	$("#upload").click(function(event){
		event.preventDefault();
		var title=$("#title").prop("value");
		var discription=$("#discription").prop("value");
		var tags=$("#tags").prop("value");
		var price=$("#price").prop("value");
		if(title===''||discription===''||tags===''||price===''){
			alert('请完整填写商品信息');
			return false;
		}
		else if($('#pic').get(0).files.length>5){
			alert('不能上传5个以上的图片');
			return false;
		}
		else{
			$("#uploadform").submit();
			$("#upload").prop('value','上传中，服务器小水管，请稍候...');
		}
	})

	//自动填入收件人名字
	$(".contactbutton").click(function(event){
		event.preventDefault();
		$("#mailreceiver").prop('value',$(this).prop('title'));
	})
	//点击发送按钮发送站内信
	$("#sendbutton").click(function(event){
		event.preventDefault();
		sendmail();
	})
	//商品详情页面小图变大图
	$(".lipicture img").click(function(event){
		var clickpic=$(this).prop('src');
		$('#smallpicture').prop('src',clickpic);
		$('#smallpicturea').prop('href',clickpic);	
	})

	//以下是ajax部分
	function createxhr(){
        var xmlhttp;
        if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp=new XMLHttpRequest();
        }
        else {// code for IE6, IE5
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        return xmlhttp;
    }

    function len(str){
        var len = 0;
        for (var i=0; i<str.length; i++) { 
            var c = str.charCodeAt(i); 
          //单字节加1 
            if ((c >= 0x0001 && c <= 0x007e) || (0xff60<=c && c<=0xff9f)) { 
                len++; 
            } 
            else { 
                len+=2; 
            } 
        } 
        return len;
    }

    function nameValidateblur(str){//这是给blur事件的-------------------------------------------------------
        if (len(str) <4 || len(str)>14){ 
            document.getElementById("valid").innerHTML="用户名长度应该在4到14位之间";
            $("#valid").show(300);
        }
        else {
            xmlhttp=createxhr();
            xmlhttp.onreadystatechange=function(){
                if (xmlhttp.readyState===4 && xmlhttp.status===200){
                    var exist=xmlhttp.responseText;
                    if (exist=="yes"){
                        document.getElementById("valid").innerHTML='已经被注册了';
                        valid= 'yes';
                        $("#valid").show(300);
                    }
                    if (exist=="no"){
                        valid= 'no';
                        $("#valid").hide(300);
                    }
                }
            }
            var rand=Math.random()*9999999;
            xmlhttp.open("GET","ajax/nameValidate.php?name="+str+"&rand="+rand,false);
            xmlhttp.send();
        }
    }
    /*

    //-----------------------------------------------------------
    function nameValidate(str){
        if (len(str) <4 || len(str)>14){ 
            document.getElementById("valid").innerHTML="用户名长度应该在4到14位之间";
            $("#valid").show(300);
      		  valid='short';
      	}

        else {
            xmlhttp=createxhr();

            xmlhttp.onreadystatechange=function(){
            	  if (xmlhttp.readyState==4 && xmlhttp.status==200){
              			var exist=xmlhttp.responseText;
              			if (exist=="yes"){
                				document.getElementById("valid").innerHTML='已经被注册了';
                        valid= 'yes';
              			}
              			if (exist=="no"){
                      valid= 'no';
              			}
              	}
            }
          	var rand=Math.random()*9999999;
          	xmlhttp.open("GET","ajax/nameValidate.php?name="+str+"&rand="+rand,false);
          	xmlhttp.send();
        }
        nameValidatehandle(valid)
    }

    function nameValidatehandle(valid){
          if (valid=="short"){ 
              document.getElementById("valid").innerHTML="用户名长度应该在4到14位之间";
              $("#valid").show(300);
              return false
          }
          else if (valid=="yes"){
            document.getElementById("valid").innerHTML='已经被注册了';
            $("#valid").show(300);
            return false
          }
          else if (valid=="no"){
            $("#registerform").submit();
          }  
    }
    */
    //添加标签
    function gettag(tags){
        var tagsarry=tags.split(' ');
        thetag=tagsarry[tagsarry.length-1];
        return thetag;
    }

    function searchtag(tags){
        if(len(tags)===0){
            $('#tagtest').hide(300);
            return;
        }

        var tagsarray=tags.split(' ');
        var keytag=tagsarray[tagsarray.length-1];
        tagsarray.pop();
        var remaintag=tagsarray.join(' ');

        xmlhttp=createxhr();

        xmlhttp.onreadystatechange=function(){
            if (xmlhttp.readyState===4 && xmlhttp.status===200){
                $('#tagtest').html(xmlhttp.responseText).hide().show();
                $(".tagtochoose").click(function(event){//给tagtochoose绑定事件
                    remaintag=remaintag+this.innerHTML+' ';
                    $("#tags").prop("value",remaintag);
                })
            }
        }
        var rand=Math.random()*9999999;
        xmlhttp.open("GET","ajax/searchtag.php?keytag="+keytag+"&rand="+rand,true);
        xmlhttp.send();
    }


    //发站内信---------------------------

    function sendmail(){
      
        xmlhttp=createxhr();

        xmlhttp.onreadystatechange=function(){
            if (xmlhttp.readyState===4 && xmlhttp.status===200) {
                if (xmlhttp.responseText=="mail success"){
                    alert("发送成功");
                    $("#sendmaildialog").hide(300);
                    $("#mailarea,#itemarea").css({
                        "opacity":1,
                    });
                }
            }
        }

        var receiver=$("#mailreceiver").prop('value');
        var mailcontent=$("#mailcontent").prop('value');

        var rand=Math.random()*9999999;
        xmlhttp.open("GET","ajax/mailajax.php?receiver="+receiver+"&content="+mailcontent+"&rand="+rand,true);

        xmlhttp.send(); 
    }

  //删除站内信---------------------


    $(".deletemail").click(deletmail);

    function deletmail(){
    
        xmlhttp=createxhr();

        todelete=this.id;

        xmlhttp.onreadystatechange=function(){
            if (xmlhttp.readyState===4 && xmlhttp.status===200) {
                if (xmlhttp.responseText==="success"){
                    deletedivid='#mailblock'+todelete;
                    $(deletedivid).hide(300);
                }
            }
        }
        var rand=Math.random()*9999999;
        xmlhttp.open("GET","ajax/deletemailajax.php?todelete="+todelete+"&rand="+rand,true);

        xmlhttp.send();
    }

//改变售出状态
    $(".changesold").click(changesold);
    function changesold(event){
        var sold=$(this).html();
        if (sold==="已售出"){
            sold=1;
        }
        else if(sold==="待售中"){
            sold=0;
        }

        var id=$(this).prop('id');
        id=id.replace('itemsold','');
        //上为准备变量

        xmlhttp=createxhr();

        xmlhttp.onreadystatechange=function(){
            if (xmlhttp.readyState===4 && xmlhttp.status===200) {
                if (xmlhttp.responseText=="changetosold"){
                    $('#itemsold'+id).html('待售中').prop('class','soldbutton changesold itemsold label label-default pull-left');
                }
                else if(xmlhttp.responseText==="changetonotsold"){
                    $('#itemsold'+id).html('已售出').prop('class','soldbutton itemsold label label-success pull-left"');
                }
            }
        }
        var rand=Math.random()*9999999;
        xmlhttp.open("GET","ajax/itemchangesold.php?sold="+sold+"&id="+id+"&rand="+rand,true);

        xmlhttp.send();
    }

    $(".moreitem").click(moreitem);
    function moreitem(e){
        e.preventDefault();
        var curpage=$("#tempcurpage").prop("value");
        var keyword=$("#tempkeyword").prop("value");
        var option=$("#tempoption").prop("value");

        $(".moreitem").html('加载中...');
        xmlhttp=createxhr();

        xmlhttp.onreadystatechange=function(){
            if (xmlhttp.readyState===4 && xmlhttp.status===200){
                if (xmlhttp.responseText==='end'){
                    $('.moreitem').html('木有更多商品了...');
                }
                else {
                    $('.moreitem,.temp').remove();
                    $('.itemarea').append(xmlhttp.responseText);
                    $(".moreitem").click(moreitem);
                }
            }
        }
        
        curpage++;
        var rand=Math.random()*9999999;
        xmlhttp.open("GET","ajax/showitemajax.php?curpage="+curpage+"&keyword="+keyword+"&option="+option+"&rand="+rand,true);

        xmlhttp.send();
    }


//删除物品
    $(".itemdelete").click(itemdelete);
    function itemdelete(event){
        var todelete=$(this).prop('id');
        todelete=todelete.replace('deleteitem','');
        xmlhttp=createxhr();

        xmlhttp.onreadystatechange=function(){
            if (xmlhttp.readyState===4 && xmlhttp.status===200) {
                if (xmlhttp.responseText==="success"){
                    deletedivid='#itemblock'+todelete;
                    $(deletedivid).hide(300);
                }
            }
        }

        var rand=Math.random()*9999999;
        xmlhttp.open("GET","ajax/deleteitemajax.php?todelete="+todelete+"&rand="+rand,true);

        xmlhttp.send();
    }

})