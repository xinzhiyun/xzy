var charge = new Vue({
	el: '#charge',
	data() {
		return {
			href: location.href,
			mealList: [],		//套餐数据
			money: '',			// 充值的套餐金额
			class2: '',			// 流程第二步
			class3: '',			// 流程第三步（走完）
			mainShow: 'meal',	// 显示套餐meal，信息info，还是完成done
			meal_id: '',		// 选择的套餐id
			uname: '',			//用户名
			uphone: '',			// 电话
			uaddr: '',			// 地址
			uaddrdetail: '',	// 详细地址
		}
	},
	watch: {},
	methods: {
		// 选择套餐
		select_meal: function(e){
			var ele = e.currentTarget;
			var meal = document.querySelectorAll('.meal');
			meal.forEach(function(meal, index){
				meal.setAttribute('class', 'meal tcenter');
			});
			// 获取套餐id
			this.meal_id = ele.getAttribute('meal_id');
			// 获取套餐金额
			this.money = ele.getAttribute('money');
			ele.setAttribute('class', 'meal tcenter select');
		},
		// 下一步
		next: function(){
			var that = this;
			if(this.mainShow == 'meal'){
				// 当前为套餐选择界面
				if(!this.meal_id || !this.money){
					noticeFn({text: '请选择套餐!'});
					return
				}
				// 下一步为信息录入
				location.href = this.href + '?info&meal_id=' + this.meal_id + '&money=' + this.money;
			}else if(this.mainShow == 'info'){
				var buyinfo = {};		// 发送给后台生成订单号的数据
				buyinfo['meal_id'] = this.meal_id;
				buyinfo['money'] = this.money;
				buyinfo['name'] = this.uname;
				buyinfo['phone'] = this.uphone;
				buyinfo['addr'] = this.uaddr;
				buyinfo['addrdetail'] = this.uaddrdetail;

				// 当前录入信息界面
				var origin = location.origin;
				var pathname = location.pathname;
				if(!this.uname){
					noticeFn({text:'请输入用户名!'});
					$('.uname').css({border: '1px solid red'});
					return
				}else if(!nameCheck(trimFn(this.uname))){
					noticeFn({text:'请输入用户名!'});
					$('.uname').css({border: '1px solid red'});
					return
				}else{
					$('.uname').css({border: 'none'});
				}
				if(!this.uphone){
					noticeFn({text:'请输入手机号!'});
					$('.uphone').css({border: '1px solid red'});
					return
				}else if(!phoneCheck(trimFn(this.uphone))){
					noticeFn({text:'请输入正确的手机号!'});
					$('.uphone').css({border: '1px solid red'});
					return
				}else{
					$('.uphone').css({border: 'none'});
				}
				if(!this.uaddr){
					noticeFn({text:'请选择地址!'});
					return
				}
				if(!this.uaddrdetail){
					noticeFn({text:'请输入详细地址!'});
					$('.uaddrdetail').css({border: '1px solid red'});
					return
				}else{
					$('.uaddrdetail').css({border: 'none'});
				}
				// 发送客户信息和订单信息，让后台生成订单号
				getOrderid(buyinfo, function(orderid){
					// 订单生成成功
					if(orderid){
						// 查询订单信息，支付信息, 用于 微信支付
						checkOrderid(orderid, function(res){
							if(res == -1){
								noticeFn({text: '支付出错， 请稍后再试！'});
							}else{
								// 微信支付
								weixinPay(res, function(res){
									if(res.status === 'ok'){
										// 支付成功
										location.href = origin + pathname + '?done';
									}else if(res.status === 'cancel'){
										// 取消支付
									}else if(res.status === 'failed'){
										// 支付失败
									}
								})
							}
						})
					}
					
				})
			}
		},
		// 获取套餐数据
		getMeal: function(){
			// 套餐数据
			this.mealList = [
				{meal_id: 1, content: '100元/100天', money:'100'},
				{meal_id: 2, content: '300元/300天', money:'300'},
				{meal_id: 3, content: '500元/500天', money:'500'}
			];
		}
	},
	created() {
		var href = this.href;
		if(href.indexOf('info') > -1){
			var moneyindex = href.indexOf('money=')+6;
			this.money = href.substring(moneyindex);
			this.mainShow = 'info';		// 信息选择

		}else if(href.indexOf('done') > -1){
			this.mainShow = 'done';		// 完成选择
			$('.next').hide();

		}else if(href.indexOf('meal') > -1){
			this.mainShow = 'meal';		// 套餐选择
			this.getMeal();				// 获取套餐信息

		}else{
			this.mainShow = 'meal';		// 套餐选择
			this.getMeal();				// 获取套餐信息
		}
		
	},
	mounted() {
		// var meal2 = document.querySelectorAll('.meal')[1];
		// meal2.setAttribute('class', 'meal tcenter select');
		// // 获取套餐id
		// this.meal_id = meal2.getAttribute('meal_id');
	},
})
document.onready = function(){
	
	// 选择地区
	$(".areabtn").on("click", function(){
		$("#areaChoose").fadeIn('fast');
	});

	// 关闭地区选择，并显示到对应区域
	$(".area").on("click", 'p', function(){
		
		var province = $('.ptext').text(),
			city = $('.ctext').text(),
			area = $(this).text();

		if(province && area){
			$('.areabtn').eq(1).hide();
		}else {
			$(".uaddr").text('');
			$('.areabtn').eq(1).show();
		}
		charge.uaddr = province + ' ' + city + ' ' + area;
		$(".uaddr").text( (!province && !city && !area) ? '请选择' : province + ' ' + city + ' ' + area);
		$(".uaddr").css({display:'inline-block'});
		// console.log($(this).text().indexOf('请选择'));
		if($(this).text().indexOf('请选择') == -1){
			setTimeout(function(){
				$("#areaChoose").fadeOut('fast');
				$('.choosebtn').hide();

			},300);
		}

	});
	// 密码可见不可见切换
	$("#seepwd").click(function(){
		if($(this).hasClass('am-icon-eye-slash')){
			$(this).attr('class', 'am-u-sm-2 am-icon-eye');
			// console.log($(this).siblings('.upwd'))
			$(this).siblings('.upwd').attr('type','text');

		}else{
			$(this).attr('class', 'am-u-sm-2 am-icon-eye-slash');
			$(this).siblings('.upwd').attr('type','password');

		}
	})
	// 点击选择地区
	$(".areabtn").click(function(){
		$("#areaChoose").fadeIn('fast');
		$('.atop>p').text('');	//确定按钮不显示
		// 清空城市， 区县
		$('.ctext').text('');
		$('.atext').text('');

	    $('#areaChoose').citys({
	    	required: false,
	    	nodata: 'disabled',
	        onChange:function(info){
	        	// townFormat(info);
	        }
	    },function(api){
	        var info = api.getInfo();
	        // townFormat(info);
	    });
	})
	// 选择城市的时候判断有没有区县
	$('.city').on('click', 'p', function(){
		// console.log($('.area>p').length)
		setTimeout(function(){
			// 没有区县可选
			if($('.area>p').length <= 1){
				$('.atop>p').text('确定');

			}else{
				$('.atop>p').text('');
			}
		},0)
	})
	// 地址面板点击确定(没有区县可选时候出现)
	$('.atop>p').on('click',function(){
		var province = $('.ptext').text(),
				city = $('.ctext').text(),
				area = $('.atext').text();

		if(city.indexOf('请选择') > -1){
			noticeFn({text:'请选择一个有有效的地址！'});
			return
		}
		if(area.indexOf('请选择') > -1){
			noticeFn({text:'请选择一个有效的地址！'});
			return
		}
		$('.uaddress').text(province + ' ' + city + ' ' + area);
		$("#areaChoose").fadeOut('fast');	//关闭地区选择面板
		$('.choosebtn').hide();	// 隐藏请选择

	})
	// 点击 xx 关闭地区选择面板
	$(".close").on('click',function(){
		$("#areaChoose").fadeOut('fast');
		
		if(!$(".uaddress").text()){
			//请选择
			$('.choosebtn').show();
		}
	})
	// 点击选择省份，城市，区县
	$('.ltext').on('click', 'p', function(){
		for(var i=0; i<$('.province>p').length; i++){
			$('.province>p').removeClass('selected');
		}
		$(this).toggleClass('selected fblue');
		
	})
	$('.areadiv').on('click','p', function(){
		// console.log($(this).text())
		if($(this).text().indexOf('请选择') > -1){
			noticeFn({text:'请选择一个有有效的地址！'});
		}
	})
	// 显示服务站列表
	$('.service_stop').click(function(){
		var text = $(this).children('span').text();
		console.log(text);
		if(text.indexOf('无') > -1){
			noticeFn({text: '当前位置附近无服务站数据！'});
			return
		}
		$('#serviceChoose').fadeIn('fast');
	})
	// 关闭服务站选择
	$('.icon-quxiao').click(function(){
		$('#serviceChoose').fadeOut('fast');
	})
	// 选择服务站
	$('.serviceul').on('click', 'li', function(){
		var text = $(this).text();
		$('.serviceul>li').removeClass('fblue');
		$(this).toggleClass('fblue');
		$('.service_stop>span').text(text);
	})
}