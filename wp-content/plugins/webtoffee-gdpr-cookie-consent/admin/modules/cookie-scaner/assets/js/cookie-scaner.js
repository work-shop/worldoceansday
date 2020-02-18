(function( $ ) {
	//'use strict';
	$(function() {
		var CLI_cookie_scanner={
			continue_scan:1,
			onPrg:0,
			Set:function()
			{
				$('.cli_scan_now').click(function(){
					CLI_cookie_scanner.continue_scan=1;
					CLI_cookie_scanner.doScan();
				});
				this.attachScanImport();
			},
			doScan:function()
			{
				var data = {
		            action: 'cli_cookie_scaner',
		            security: cookielawinfo_cookie_scaner.nonces.cli_cookie_scaner,
		            cli_scaner_action:'check_api',
		        };
		        var scanbar=$('.cli_scanbar');
		        scanbar.html('<span style="float:left; height:40px; line-height:40px;">'+cookielawinfo_cookie_scaner.labels.checking_api+'</span> <img src="'+cookielawinfo_cookie_scaner.loading_gif+'" style="display:inline-block;" />');
		        $.ajax({
		            url: cookielawinfo_cookie_scaner.ajax_url,
		            data: data,
		            dataType:'json',
		            type: 'POST',
		            success: function (data) 
		            {
		                scanbar.html('');
		                if(data.response===true)
		                {
		                	CLI_cookie_scanner.scanNow();
		                }else
		                {
		                	CLI_cookie_scanner.serverUnavailable(scanbar,data.message);
		                }
		            },
		            error:function()
		            {
		            	scanbar.html('');
		            	CLI_cookie_scanner.showErrorScreen(cookielawinfo_cookie_scaner.labels.error);
		            }
		        });
			},
			serverUnavailable:function(elm,msg)
			{
				elm.html('<div style="background:#fff; border:solid 1px #ccc; color:#333; padding:5px;">'+msg+'</div>');
				$('.cli_scanner_send_report').click(function(){
					CLI_cookie_scanner.sendServerDownReport();
				});
				$('.cli_scanner_not_send_report').click(function(){
					CLI_cookie_scanner.notSendServerDownReport();
				});
			},
			notSendServerDownReport:function()
			{
				$('.cli_scanbar').html('');
				cli_notify_msg.success(cookielawinfo_cookie_scaner.labels.thankyou);
			},
			sendServerDownReport:function()
			{
				var data = {
		            action: 'cli_cookie_scaner',
		            security: cookielawinfo_cookie_scaner.nonces.cli_cookie_scaner,
		            cli_scaner_action:'report_now',
		        };
		        $('.cli_scanner_send_report').replaceWith('<span class="cli_report_send_loader">'+cookielawinfo_cookie_scaner.labels.sending+'</span>');
				$('.cli_scanner_not_send_report').remove();
				$.ajax({
					url: cookielawinfo_cookie_scaner.ajax_url,
		            data: data,
		            dataType: 'json',
		            type: 'POST',
		            success: function (data) 
		            {
		               	$('.cli_scanbar').html('');
		                $('.cli_report_send_loader').remove();
		                cli_notify_msg.success(cookielawinfo_cookie_scaner.labels.thankyou);
		            },
		            error:function()
		            {
		            	$('.cli_scanbar').html('');
		            	$('.cli_report_send_loader').remove();
		            	CLI_cookie_scanner.showErrorScreen(cookielawinfo_cookie_scaner.labels.error);
		            }
				});
			},
			scanAgain:function()
			{
				$('.cli_scan_again').unbind('click').click(function(){
					CLI_cookie_scanner.continue_scan=1;
					CLI_cookie_scanner.scanNow();
				});
			},
			scanNow:function()
			{				
				var html=this.makeHtml();
				var scanbar=$('.cli_scanbar');
				scanbar.html(html);
				$('.cli_scanbar_staypage').show();
				this.attachScanStop();
				$('.cli_scanlog').css({'display':'block','opacity':0}).animate({
					'opacity':1,'height':350
				},1000);
				this.takePages(0);
			},
			takePages:function(offset,limit,total,scan_id)
			{
				var data = {
		            action: 'cli_cookie_scaner',
		            security: cookielawinfo_cookie_scaner.nonces.cli_cookie_scaner,
		            cli_scaner_action:'get_pages',
		            offset:offset
		        };
		        if(limit)
		        {
		        	data['limit']=limit;
		        }
		        if(total)
		        {
		        	data['total']=total;
		        }
		        if(scan_id)
		        {
		        	data['scan_id']=scan_id;
		        }
		        //fake progress
				this.animateProgressBar(1,100,cookielawinfo_cookie_scaner.labels.finding);
		        $.ajax({
		            url: cookielawinfo_cookie_scaner.ajax_url,
		            data: data,
		            dataType: 'json',
		            type: 'POST',
		            success: function (data) 
		            {
		                CLI_cookie_scanner.scan_id=typeof data.scan_id!='undefined' ? data.scan_id : 0;
		                if(CLI_cookie_scanner.continue_scan==0)
		                {
		                	return false;
		                }
		                if(typeof data.response!='undefined' && data.response===true)
		                {
		                	CLI_cookie_scanner.appendLogAnimate(data.log,0);
		                	var new_offset=parseInt(data.offset)+parseInt(data.limit);
							if((data.total-1)>new_offset) //substract 1 from total because of home page
							{
								CLI_cookie_scanner.takePages(new_offset,data.limit,data.total,data.scan_id);
							}else
							{
								$('.cli_progress_action_main').html(cookielawinfo_cookie_scaner.labels.scanning);
								CLI_cookie_scanner.scanPages(data.scan_id,0,data.total);
							}
		                }else
		                {
		                	CLI_cookie_scanner.showErrorScreen(cookielawinfo_cookie_scaner.labels.error);
		                }
		            },
		            error:function()
		            {
		            	if(CLI_cookie_scanner.continue_scan==0)
		                {
		                	return false;
		                }
		                CLI_cookie_scanner.showErrorScreen(cookielawinfo_cookie_scaner.labels.error);
		                //======
		            }
		        });
			},
			scanPages:function(scan_id,offset,total)
			{
				var data = {
		            action: 'cli_cookie_scaner',
		            security: cookielawinfo_cookie_scaner.nonces.cli_cookie_scaner,
		            cli_scaner_action:'scan_pages',
		            offset:offset,
		            scan_id:scan_id,
		            total:total
		        };
				$.ajax({
					url: cookielawinfo_cookie_scaner.ajax_url,
		            data: data,
		            dataType: 'json',
		            type: 'POST',
		            success:function(data)
		            {
	            		CLI_cookie_scanner.scan_id=typeof data.scan_id!='undefined' ? data.scan_id : 0;
		                if(CLI_cookie_scanner.continue_scan==0)
		                {
		                	return false;
		                }
	            		var prg_offset=parseInt(offset)+parseInt(data.total_scanned);
	            		var prg_msg=cookielawinfo_cookie_scaner.labels.scanned+': '+prg_offset+' URLs';
	            		CLI_cookie_scanner.appendLogAnimate(data.log,0);
		            	if(data.continue===true)
		            	{
		            		CLI_cookie_scanner.scanPages(data.scan_id,data.offset,data.total);
		            	}else
		            	{
		            		prg_msg=cookielawinfo_cookie_scaner.labels.finished;
		            		prg_msg+=' ('+cookielawinfo_cookie_scaner.labels.total_urls_scanned+': '+data.total;
		            		prg_msg+=' ,'+cookielawinfo_cookie_scaner.labels.total_cookies_found+': '+data.total_cookies+')';
		            		CLI_cookie_scanner.showSuccessScreen(prg_msg,scan_id,1);
		            	}
		            	CLI_cookie_scanner.animateProgressBar(prg_offset,total,prg_msg);
		            },
		            error:function()
		            {
		            	if(CLI_cookie_scanner.continue_scan==0)
		                {
		                	return false;
		                }
		            	//error and retry function
		            	CLI_cookie_scanner.animateProgressBar(offset,total,cookielawinfo_cookie_scaner.labels.retrying);
		            	setTimeout(function(){
		            		CLI_cookie_scanner.scanPages(scan_id,offset,total);
		            	},2000);		            	
		            }
				});			
			},
			animateProgressBar:function(offset,total,msg)
			{
				var prgElm=$('.cli_progress_bar');
				var w=prgElm.width();
				var sp=100/total;
				var sw=w/total;
				var cw=sw*offset;
				var cp=sp*offset;

				cp=cp>100 ? 100 : cp;
				cp=Math.floor(cp<1 ? 1 : cp);

				cw=cw>w ? w : cw;
				cw=Math.floor(cw<1 ? 1 : cw);
				$('.cli_progress_bar_inner').stop(true,true).animate({'width':cw+'px'},300,function(){
					$('.cli_progress_action_main').html(msg);
				}).html(cp+'%');
			},
			makeHtml:function()
			{
				return '<div class="cli_scanlog">'
					+'<div class="cli_progress_action_main">'+cookielawinfo_cookie_scaner.labels.finding+'</div>'
					+'<div class="cli_progress_bar">'
					+'<span class="cli_progress_bar_inner">'
					+'</span>'
					+'</div>'
					+'<div class="cli_scanner_ajax_log"></div>'
					+'<div class="cli_scanlog_bar"><a class="button-primary pull-right cli_stop_scan">'+cookielawinfo_cookie_scaner.labels.stop+'</a></div>'
					+'</div>';
			},
			appendLogAnimate:function(data,offset)
			{
				if(data.length>offset)
				{
					$('.cli_scanner_ajax_log').append(data[offset]+'<br />').scrollTop($('.cli_scanner_ajax_log')[0].scrollHeight);
					offset++;
					var speed=300/data.length;
					setTimeout(function(){
						CLI_cookie_scanner.appendLogAnimate(data,offset);
					},speed);
				}
			},
			showErrorScreen:function(error_msg)
			{
				var html='<a class="button-primary pull-right cli_scan_again" style="margin-left:5px;">'+cookielawinfo_cookie_scaner.labels.scan_again+'</a>';
				html+='<span class="pull-right">'+error_msg+'</span>';
				$('.cli_scanlog_bar').html(html);
				$('.cli_progress_action_main').html(error_msg);
				cli_notify_msg.error(error_msg);
				$('.cli_scanbar_staypage').hide();
				this.scanAgain();
			},
			showSuccessScreen:function(success_msg,scan_id,total)
			{
				var html='<a class="button-primary pull-right cli_scan_again" style="margin-left:5px;">'+cookielawinfo_cookie_scaner.labels.scan_again+'</a>';
				if(total==1)
				{
					html+='<a class="button-secondary pull-right cli_export" style="margin-left:5px;" href="'+cookielawinfo_cookie_scaner.export_page_url+scan_id+'">'+cookielawinfo_cookie_scaner.labels.export+'</a>';
					html+='<a class="button-secondary pull-right cli_import" style="margin-left:5px;" data-scan_id="'+scan_id+'">'+cookielawinfo_cookie_scaner.labels.import+'</a>';
				}
				html+='<a class="button-secondary pull-right cli_view_scan_result" style="margin-left:5px;" href="'+cookielawinfo_cookie_scaner.result_page_url+'">'+cookielawinfo_cookie_scaner.labels.view_result+'</a>';
				html+='<span class="spinner" style="margin-top:5px"></span>';
				html+='<span class="pull-right cli_scan_success_bottom" style="font-weight:bold;">'+success_msg+'</span>';
				$('.cli_scanlog_bar').html(html);
				$('.cli_progress_action_main').html(success_msg);
				cli_notify_msg.success(success_msg);
				$('.cli_scanbar_staypage').hide();
				this.attachScanImport(scan_id);
				this.scanAgain();
			},
			attachScanImport:function(scan_id)
			{
				$('.cli_import').unbind('click').click(function(){
					var scan_id=$(this).attr('data-scan_id');
					if($('.cli_import_popup').length==0)
					{
						var html='<div class="cli_import_popup"><h2>'+cookielawinfo_cookie_scaner.labels.import_options+'</h2> '
						+'<input type="radio" name="cli_import_options" id="cli_import_options_replace" value="1" /><label for="cli_import_options_replace"> '+cookielawinfo_cookie_scaner.labels.replace_old+'</label><br />'
						+'<input type="radio" name="cli_import_options" id="cli_import_options_merge" value="2" checked /><label for="cli_import_options_merge"> '+cookielawinfo_cookie_scaner.labels.merge+' ('+cookielawinfo_cookie_scaner.labels.recommended+')</label> <br />'
						+'<input type="radio" name="cli_import_options" id="cli_import_options_append" value="3" /><label for="cli_import_options_append"> '+cookielawinfo_cookie_scaner.labels.append+' ('+cookielawinfo_cookie_scaner.labels.not_recommended+')</label> <br /><br />'
						+'<a class="button-secondary pull-left cli_import_cancel">'+cookielawinfo_cookie_scaner.labels.cancel+'</a>'
						+'<a class="button-primary pull-left cli_import_now" data-scan_id="'+scan_id+'" style="margin-left:5px;">'+cookielawinfo_cookie_scaner.labels.start_import+'</a>'
						'</div>';
						$('body').append(html);
						$('.cli_import_cancel').click(function(){
							$('.cli_import_popup').hide();
						});
						$('.cli_import_now').click(function(){
							var import_option=$('[name="cli_import_options"]:checked').val();
							var scan_id=$(this).attr('data-scan_id');
							$('.cli_import_popup').hide();
							CLI_cookie_scanner.importNow(scan_id,import_option);
							CLI_cookie_scanner.hideProgressbarAndLog();
						});
					}else
					{
						$('.cli_import_popup').show();
					}
				});

			},
			hideProgressbarAndLog:function()
			{
				if($('.cli_scanlog').length>0)
				{
					$('.cli_scanner_ajax_log').animate({'height':'0px'},200,function(){
						$(this).hide();
						$('.cli_progress_bar').hide();
					});
					$('.cli_scanlog').animate({'height':'100px'});
					$('.cli_scan_success_bottom').html('');
				}
			},
			attachScanStop:function()
			{
				$('.cli_stop_scan').click(function(){
					CLI_cookie_scanner.stopScan();
				});
			},
			importNow:function(scan_id,import_option)
			{
				if(this.onPrg==1)
				{
					return false;
				}
				var data = {
		            action: 'cli_cookie_scaner',
		            security: cookielawinfo_cookie_scaner.nonces.cli_cookie_scaner,
		            cli_scaner_action:'import_now',
		            scan_id:scan_id,
		            import_option:import_option
		        };
		        $('.wrap a').css({'opacity':.5});
		        $('.cli_import').html(cookielawinfo_cookie_scaner.labels.importing);
		        $('.cli_progress_action_main').html(cookielawinfo_cookie_scaner.labels.importing);
		        $('.spinner').css({'visibility':'visible'});
		        this.onPrg=1;
				$.ajax({
					url:cookielawinfo_cookie_scaner.ajax_url,
					data:data,
					dataType:'json',
					type:'POST',
					success:function(data)
					{
						CLI_cookie_scanner.onPrg=0;
						$('.wrap a').css({'opacity':1});
						$('.cli_import').html(cookielawinfo_cookie_scaner.labels.import);
						$('.cli_progress_action_main').html(cookielawinfo_cookie_scaner.labels.import_finished);
						$('.spinner').css({'visibility':'hidden'});
						if(data.response===true)
						{
							CLI_cookie_scanner.refreshCookieList(); //in scanning page
							cli_notify_msg.success(data.message);
						}else
						{
							cli_notify_msg.error(data.message);
						}
					},
		            error:function()
		            {
		            	CLI_cookie_scanner.onPrg=0;
		            	$('.wrap a').css({'opacity':1});
						$('.cli_import').html(cookielawinfo_cookie_scaner.labels.import);
						$('.cli_progress_action_main').html(cookielawinfo_cookie_scaner.labels.error);
						$('.spinner').css({'visibility':'hidden'});
		            	cli_notify_msg.error(cookielawinfo_cookie_scaner.labels.error);		            			            	
		            }
				});
			},
			refreshCookieList:function()
			{
				if($('.cli_existing_cookie_list').length>0)
				{
					$('.cli_existing_cookie_list').html("<h3>"+cookielawinfo_cookie_scaner.labels.refreshing+"</h3>");
					$.ajax({
						type:'GET',
						success:function(data)
						{
							var temp_dv=$('<div />').html(data);
							var html=temp_dv.find('.cli_existing_cookie_list').html();
							$('.cli_existing_cookie_list').html(html);
						},
						error:function()
						{
							$('.cli_existing_cookie_list').html("<h3>"+cookielawinfo_cookie_scaner.labels.reload_page+"</h3>");
						}
					});
				}
			},
			stopingScan:function(scan_id)
			{
				var data = {
		            action: 'cli_cookie_scaner',
		            security: cookielawinfo_cookie_scaner.nonces.cli_cookie_scaner,
		            cli_scaner_action:'stop_scan',
		            scan_id:scan_id
		        };
		        $('.cli_stop_scan').html(cookielawinfo_cookie_scaner.labels.stoping).css({'opacity':'.5'});
				$.ajax({
					url: cookielawinfo_cookie_scaner.ajax_url,
		            data: data,
		            dataType: 'json',
		            type: 'POST',
		            success:function(data)
		            {
	            		CLI_cookie_scanner.showSuccessScreen(cookielawinfo_cookie_scaner.labels.scanning_stopped,scan_id,data.total);
		            },
		            error:function()
		            {
		            	//error function
		            	CLI_cookie_scanner.showErrorScreen(cookielawinfo_cookie_scaner.labels.error);		            	
		            }
				});
			},
			stopScan:function()
			{
				if(CLI_cookie_scanner.continue_scan==0)
				{
					return false;
				}
				if(confirm(cookielawinfo_cookie_scaner.labels.ru_sure))
				{
					CLI_cookie_scanner.continue_scan=0;
					this.stopingScan(CLI_cookie_scanner.scan_id);
				}
			}
		}

		CLI_cookie_scanner.Set();
	});
})( jQuery );