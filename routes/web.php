<?php
    Route::get('/', function(){
        return view ('welcome');
    });

    Route::post('check-unique',['as'=>'check-unique','uses'=>'Tools@postCheckUnique']);
	Route::post('change-language',['as'=>'change-language','uses'=>'Tools@postChangeLanguage']);
/**
 * ======================================================================================================
 * 					Admin routing start from here
 * ======================================================================================================
 */
	Route::get(ADMIN_PATH,['as'=>'admin','uses'=>'admin\Authentication@getLogin']);
	Route::get(ADMIN_PATH.'/login',['as'=>'admin-login','uses'=>'admin\Authentication@getLogin']);
	Route::post(ADMIN_PATH.'/login',['as'=>'admin-post-login','uses'=>'admin\Authentication@postLogin']);
	Route::get(ADMIN_PATH.'/forgot-password',['as'=>'admin-get-forgot-password','uses'=>'admin\Authentication@getForgotPassword']);
	Route::post(ADMIN_PATH.'/forgot-password',['as'=>'admin-post-forgot-password','uses'=>'admin\Authentication@postForgotPassword']);

	Route::get(ADMIN_PATH.'/reset-password/{unique_code}',['as'=>'admin-get-reset-password','uses'=>'admin\Authentication@getResetPassword']);
	Route::post(ADMIN_PATH.'/reset-password',['as'=>'admin-post-reset-password','uses'=>'admin\Authentication@postResetPassword']);

	Route::group(['prefix'=> ADMIN_PATH,'as' => 'admin-','middleware'=>'admin.auth','lang','namespace'=>'admin'],function(){

		Route::get('dashboard',['as'=>'dashboard','uses'=>'Authentication@index']);
		Route::get('admin-coming-soon',['as'=>'coming-soon','uses'=>'Authentication@getComingSoon']);
		Route::get('admin-profile-settings',['as'=>'profile-settings','uses'=>'Authentication@getProfileSettings']);
		Route::post('admin-profile-settings',['as'=>'profile-settings','uses'=>'Authentication@postProfileSettings']);
		Route::post('admin-logout',['as'=>'logout','uses'=>'Authentication@postLogout']);
        Route::get('unread-tickets',['as'=>'unread-tickets','uses'=>'SupportTicket/MyTicketCrud@getUnreadSupportTtickets']);


	    Route::group(['prefix'=> 'content-management','as' => 'content-management-','middleware'=>'admin.auth:content-management'],function(){
	        Route::group(['as' => 'cms-page-','prefix' => 'cms_page'],function(){
				Route::get('/',['uses'=>'CmsPageCrud@show','middleware' => 'admin.auth:cms-page-@canView']);
      			Route::get('list',['as'=>'list','uses'=>'CmsPageCrud@show','middleware' => 'admin.auth:cms-page-@canView']);
      			Route::get('view/{id?}',['as'=>'view','uses'=>'CmsPageCrud@view','middleware' => 'admin.auth:cms-page-@canView']);
      			Route::get('add',['as'=>'add','uses'=>'CmsPageCrud@add','middleware' => 'admin.auth:cms-page-@canAdd']);
      			Route::post('insert',['as'=>'insert','uses'=>'CmsPageCrud@insert','middleware' => 'admin.auth:cms-page-@canAdd']);
      			Route::get('edit/{id?}',['as'=>'edit','uses'=>'CmsPageCrud@edit','middleware' => 'admin.auth:cms-page-@canModify']);
      			Route::post('update',['as'=>'update','uses'=>'CmsPageCrud@update','middleware' => 'admin.auth:cms-page-@canModify']);
      			Route::get('delete/{id?}',['as'=>'delete','uses'=>'CmsPageCrud@delete','middleware' => 'admin.auth:cms-page-@canModify']);
	      	});
	    });
	    Route::group(['prefix'=> 'subscription-management','as' => 'subscription-management-','middleware'=>['admin.auth:subscription-management']],function(){
            Route::group(['as' => 'plans-','prefix' => 'subscription_plan', 'middleware' =>  'Exist:App\Models\SubscriptionPlan'],function(){
    			Route::get('',['uses'=>'SubscriptionPlanCrud@show','middleware'=>'admin.auth:plans-@canView']);
    			Route::get('list',['as'=>'list','uses'=>'SubscriptionPlanCrud@show','middleware'=>'admin.auth:plans-@canView']);
    			Route::post('fetch-list',['as'=>'fetch-list','uses'=>'SubscriptionPlanCrud@fetchList','middleware'=>'admin.auth:plans-@canView']);
    			Route::get('fetch-details/{id?}',['as'=>'fetch-details','uses'=>'SubscriptionPlanCrud@fetchDetails','middleware'=>'admin.auth:plans-@canView']);
    			Route::get('view/{id?}',['as'=>'view','uses'=>'SubscriptionPlanCrud@view','middleware'=>'admin.auth:plans-@canView']);
    			Route::get('add',['as'=>'add','uses'=>'SubscriptionPlanCrud@add','middleware'=>'admin.auth:plans-@canAdd']);
    			Route::post('insert',['as'=>'insert','uses'=>'SubscriptionPlanCrud@insert','middleware'=>'admin.auth:plans-@canAdd']);
    			Route::get('edit/{id?}',['as'=>'edit','uses'=>'SubscriptionPlanCrud@edit','middleware'=>'admin.auth:plans-@canModify']);
    			Route::post('update',['as'=>'update','uses'=>'SubscriptionPlanCrud@update','middleware'=>'admin.auth:plans-@canModify']);
    			Route::get('delete/{id?}',['as'=>'delete','uses'=>'SubscriptionPlanCrud@delete','middleware'=>'admin.auth:plans-@canModify']);
    		});
            Route::group(['as' => 'companies-','prefix' => 'companies' ,'middleware' => ['admin.auth:companies-@canView','Exist:App\Models\Subscriber']],function(){
                Route::get('',['uses'=>'SubscriberCrud@show', 'middleware'=>'admin.auth:companies-@canView']);
                Route::get('list',['as'=>'list','uses'=>'SubscriberCrud@show', 'middleware'=>'admin.auth:companies-@canView']);
                Route::get('view/{id?}',['as'=>'view','uses'=>'SubscriberCrud@view', 'middleware'=>'admin.auth:companies-@canView']);
                Route::get('add',['as'=>'add','uses'=>'SubscriberCrud@add', 'middleware'=>'admin.auth:companies-@canAdd']);
                Route::post('insert',['as'=>'insert','uses'=>'SubscriberCrud@insert', 'middleware'=>'admin.auth:companies-@canAdd']);
                Route::get('edit/{id?}',['as'=>'edit','uses'=>'SubscriberCrud@edit', 'middleware'=>'admin.auth:companies-@canModify']);
                Route::post('update',['as'=>'update','uses'=>'SubscriberCrud@update', 'middleware'=>'admin.auth:companies-@canModify']);
                Route::post('update-full-details',['as'=>'update-full-details','uses'=>'SubscriberCrud@updateFullDetails', 'middleware'=>['admin.auth:companies-@canModify', 'admin.auth:company-config-file@canModify']]);
                Route::get('delete/{id?}',['as'=>'delete','uses'=>'SubscriberCrud@delete', 'middleware'=>'admin.auth:companies-@canModify']);

                Route::group(['as' => 'subscription-logs-','prefix' => 'subscription-history', 'middleware' => ['admin.auth:subscription-logs-@canView','Exist:App\Models\SubscriptionLog,log_id']],function(){
        			Route::get('/{id?}',['uses'=>'SubscriptionLogCrud@show', 'middleware'=>'admin.auth:subscription-logs-@canView']);
        			Route::get('list/{id?}',['as'=>'list','uses'=>'SubscriptionLogCrud@show', 'middleware'=>'admin.auth:subscription-logs-@canView']);
        			Route::get('view/{id?}/{log_id?}',['as'=>'view','uses'=>'SubscriptionLogCrud@view', 'middleware'=>'admin.auth:subscription-logs-@canView']);
        			Route::get('add/{id?}',['as'=>'add','uses'=>'SubscriptionLogCrud@add', 'middleware'=>'admin.auth:subscription-logs-@canAdd']);
        			Route::post('insert/{id}',['as'=>'insert','uses'=>'SubscriptionLogCrud@insert', 'middleware'=>'admin.auth:subscription-logs-@canAdd']);
        			Route::get('edit/{id?}/{log_id?}',['as'=>'edit','uses'=>'SubscriptionLogCrud@edit', 'middleware'=>'admin.auth:subscription-logs-@canModify']);
        			Route::post('update/{id?}',['as'=>'update','uses'=>'SubscriptionLogCrud@update', 'middleware'=>'admin.auth:subscription-logs-@canModify']);
        			Route::get('delete/{id?}/{log_id?}',['as'=>'delete','uses'=>'SubscriptionLogCrud@delete', 'middleware'=>'admin.auth:subscription-logs-@canModify']);
        		});
                Route::group(['as' => 'crm-modules-','prefix' => 'crm-modules', 'middleware' => ['admin.auth:crm-modules-@canModify']],function(){
        			Route::get('/{id?}',['uses'=>'CrmModuleController@show', 'middleware'=>'admin.auth:crm-modules-@canView']);
        			Route::post('update/{id}',['as'=>'update','uses'=>'CrmModuleController@update', 'middleware'=>'admin.auth:crm-modules-@canAdd']);
        		});
                Route::get('send-code/{action}/{id?}',['as'=>'send-code-to-verify','uses'=>'SubscriberCrud@sendCode', 'middleware'=>'admin.auth:company-config-file@canView']);
                Route::get('view-full-details/{token?}',['as'=>'view-full-details','uses'=>'SubscriberCrud@viewFullDetails', 'middleware'=>'admin.auth:company-config-file@canView']);
                Route::get('edit-full-details/{token?}',['as'=>'edit-full-details','uses'=>'SubscriberCrud@editFullDetails', 'middleware'=>'admin.auth:company-config-file@canModify']);

            });
        });

	    Route::group(['prefix'=> 'settings','as' => 'settings-','middleware'=>'admin.auth:settings'],function(){

			Route::get('role-management',['as'=>'role-management','uses'=>'RoleManagement@listRoleManagement','middleware' => 'admin.auth:role-management@canView']);
	        Route::post('role-management',['as'=>'role-management','uses'=>'RoleManagement@postRoleManagement','middleware' => 'admin.auth:role-management@canAdd']);
	        Route::get('role-permission-management',['as'=>'role-permission-management','uses'=>'RolePermissionManagement@listRolePermissionManagement','middleware' => 'admin.auth:role-permission-management@canView']);
	        Route::post('role-permission-management',['as'=>'role-permission-management','uses'=>'RolePermissionManagement@postRolePermissionManagement','middleware' => 'admin.auth:role-permission-management@canModify']);

			Route::group(['as' => 'user-group-','prefix' => 'user-group','middleware'=>'admin.auth:user-group-'],function(){
				Route::get('/',['uses'=>'RoleCrud@show','middleware' => 'admin.auth:user-group-@canView']);
				Route::get('list',['as'=>'list','uses'=>'RoleCrud@show','middleware' => 'admin.auth:user-group-@canView']);
				Route::get('view/{id?}',['as'=>'view','uses'=>'RoleCrud@view','middleware' => 'admin.auth:user-group-@canView']);
				Route::get('add',['as'=>'add','uses'=>'RoleCrud@add','middleware' => 'admin.auth:user-group-@canAdd']);
				Route::post('insert',['as'=>'insert','uses'=>'RoleCrud@insert','middleware' => 'admin.auth:user-group-@canAdd']);
				Route::get('edit/{id?}',['as'=>'edit','uses'=>'RoleCrud@edit','middleware' => 'admin.auth:user-group-@canModify']);
				Route::post('update',['as'=>'update','uses'=>'RoleCrud@update','middleware' => 'admin.auth:user-group-@canModify']);
				Route::get('delete/{id?}',['as'=>'delete','uses'=>'RoleCrud@delete','middleware' => 'admin.auth:user-group-@canModify']);
			});
	        Route::group(['as' => 'site-settings-','prefix' => 'site_setting'],function(){
	            Route::get('/',['uses'=>'SiteSettingCrud@show','middleware' => 'admin.auth:site-settings-@canView']);
	            Route::get('list',['as'=>'list','uses'=>'SiteSettingCrud@show','middleware' => 'admin.auth:site-settings-@canView']);
	            Route::get('view/{id?}',['as'=>'view','uses'=>'SiteSettingCrud@view','middleware' => 'admin.auth:site-settings-@canView']);
	            Route::get('add',['as'=>'add','uses'=>'SiteSettingCrud@add','middleware' => 'admin.auth:site-settings-@canAdd']);
	            Route::post('insert',['as'=>'insert','uses'=>'SiteSettingCrud@insert','middleware' => 'admin.auth:site-settings-@canAdd']);
	            Route::get('edit/{id?}',['as'=>'edit','uses'=>'SiteSettingCrud@edit','middleware' => 'admin.auth:site-settings-@canModify']);
	            Route::post('update',['as'=>'update','uses'=>'SiteSettingCrud@update','middleware' => 'admin.auth:site-settings-@canModify']);
	            Route::get('delete/{id?}',['as'=>'delete','uses'=>'SiteSettingCrud@delete','middleware' => 'admin.auth:site-settings-@canModify']);
	        });
		  	Route::group(['as' => 'module-management-','prefix' => 'module','middleware' => 'admin.auth:module-management-@canView'],function(){
				Route::get('/',['uses'=>'ModuleCrud@show','middleware' => 'admin.auth:module-management-@canView']);
				Route::get('list',['as'=>'list','uses'=>'ModuleCrud@show', 'middleware' => 'admin.auth:module-management-@canView']);
				Route::get('view/{id?}',['as'=>'view','uses'=>'ModuleCrud@view', 'middleware' => 'admin.auth:module-management-@canView']);
				Route::get('add',['as'=>'add','uses'=>'ModuleCrud@add', 'middleware' => 'admin.auth:module-management-@canAdd']);
				Route::post('insert',['as'=>'insert','uses'=>'ModuleCrud@insert', 'middleware' => 'admin.auth:module-management-@canAdd']);
				Route::get('edit/{id?}',['as'=>'edit','uses'=>'ModuleCrud@edit', 'middleware' => 'admin.auth:module-management-@canModify']);
				Route::post('update',['as'=>'update','uses'=>'ModuleCrud@update', 'middleware' => 'admin.auth:module-management-@canModify']);
				Route::get('delete/{id?}',['as'=>'delete','uses'=>'ModuleCrud@delete', 'middleware' => 'admin.auth:module-management-@canModify']);
			});
	        Route::group(['as' => 'mail-template-','prefix' => 'mail_template','middleware' => 'admin.auth:mail-template-@canView'],function(){
	            Route::get('/',['uses'=>'MailTemplateCrud@show','middleware' => 'admin.auth:mail-template-@canView']);
	            Route::get('list',['as'=>'list','uses'=>'MailTemplateCrud@show','middleware' => 'admin.auth:mail-template-@canView']);
	            Route::get('view/{id?}',['as'=>'view','uses'=>'MailTemplateCrud@view','middleware' => 'admin.auth:mail-template-@canView']);
	            Route::get('add',['as'=>'add','uses'=>'MailTemplateCrud@add','middleware' => 'admin.auth:mail-template-@canAdd']);
	            Route::post('insert',['as'=>'insert','uses'=>'MailTemplateCrud@insert','middleware' => 'admin.auth:mail-template-@canAdd']);
	            Route::get('edit/{id?}',['as'=>'edit','uses'=>'MailTemplateCrud@edit','middleware' => 'admin.auth:mail-template-@canModify']);
	            Route::post('update',['as'=>'update','uses'=>'MailTemplateCrud@update','middleware' => 'admin.auth:mail-template-@canModify']);
	            Route::get('delete/{id?}',['as'=>'delete','uses'=>'MailTemplateCrud@delete','middleware' => 'admin.auth:mail-template-@canModify']);
            });
            Route::group(['as' => 'country-','prefix' => 'country','middleware' => ['admin.auth:mail-template-@canView', 'Exist:App\Models\Country']],function(){
    			Route::get('',['uses'=>'CountryCrud@show', 'middleware' => 'admin.auth:country-@canView']);
    			Route::get('list',['as'=>'list','uses'=>'CountryCrud@show', 'middleware' => 'admin.auth:country-@canView']);
    			Route::get('view/{id?}',['as'=>'view','uses'=>'CountryCrud@view', 'middleware' => 'admin.auth:country-@canView']);
    			Route::get('add',['as'=>'add','uses'=>'CountryCrud@add', 'middleware' => 'admin.auth:country-@canAdd']);
    			Route::post('insert',['as'=>'insert','uses'=>'CountryCrud@insert', 'middleware' => 'admin.auth:country-@canAdd']);
    			Route::get('edit/{id?}',['as'=>'edit','uses'=>'CountryCrud@edit', 'middleware' => 'admin.auth:country-@canModify']);
    			Route::post('update',['as'=>'update','uses'=>'CountryCrud@update', 'middleware' => 'admin.auth:country-@canModify']);
    			Route::get('delete/{id?}',['as'=>'delete','uses'=>'CountryCrud@delete', 'middleware' => 'admin.auth:country-@canModify']);
    		});
            Route::group(['as' => 'currency-','prefix' => 'currency','middleware' => ['admin.auth:currency-@canView', 'Exist:App\Models\Currency']],function(){
    			Route::get('',['uses'=>'CurrencyCrud@show', 'middleware' => 'admin.auth:currency-@canView']);
    			Route::get('list',['as'=>'list','uses'=>'CurrencyCrud@show', 'middleware' => 'admin.auth:currency-@canView']);
    			Route::get('view/{id?}',['as'=>'view','uses'=>'CurrencyCrud@view', 'middleware' => 'admin.auth:currency-@canView']);
    			Route::get('add',['as'=>'add','uses'=>'CurrencyCrud@add', 'middleware' => 'admin.auth:currency-@canAdd']);
    			Route::post('insert',['as'=>'insert','uses'=>'CurrencyCrud@insert', 'middleware' => 'admin.auth:currency-@canAdd']);
    			Route::get('edit/{id?}',['as'=>'edit','uses'=>'CurrencyCrud@edit', 'middleware' => 'admin.auth:currency-@canModify']);
    			Route::post('update',['as'=>'update','uses'=>'CurrencyCrud@update', 'middleware' => 'admin.auth:currency-@canModify']);
    			Route::get('delete/{id?}',['as'=>'delete','uses'=>'CurrencyCrud@delete', 'middleware' => 'admin.auth:currency-@canModify']);
    		});
	    });
		Route::group(['as' => 'users-','prefix' => 'user','middleware' => ['admin.auth:users-@canView', 'OnlyParent', 'Exist:App\Models\User']],function(){
			Route::get('/',['uses'=>'UserCrud@show','middleware' => 'admin.auth:users-@canView']);
			Route::get('list/{id?}',['as'=>'list','uses'=>'UserCrud@show','middleware' => 'admin.auth:users-@canView']);
			Route::get('data/{id?}',['as'=>'data','uses'=>'UserCrud@data','middleware' => 'admin.auth:users-@canView']);
			Route::get('view/{id?}',['as'=>'view','uses'=>'UserCrud@view','middleware' => 'admin.auth:users-@canView']);
			Route::get('add/{id?}',['as'=>'add','uses'=>'UserCrud@add','middleware' => 'admin.auth:users-@canAdd']);
			Route::post('insert',['as'=>'insert','uses'=>'UserCrud@insert','middleware' => 'admin.auth:users-@canAdd']);
			Route::get('edit/{id?}',['as'=>'edit','uses'=>'UserCrud@edit','middleware' => 'admin.auth:users-@canModify']);
			Route::post('update',['as'=>'update','uses'=>'UserCrud@update','middleware' => 'admin.auth:users-@canModify']);
			Route::get('delete/{id?}',['as'=>'delete','uses'=>'UserCrud@delete','middleware' => 'admin.auth:users-@canModify']);

            Route::get('/get-username/{slug?}',['as'=>'get-username', 'uses'=>'UserCrud@getUserName']);
            Route::get('/get-all-parents/{id?}',['as'=>'get-all-parents', 'uses'=>'UserCrud@getAllParentList']);
        });
        Route::group(['as' => 'user-activity-log-','prefix' => 'users-activity-log', 'middleware' => ['admin.auth:user-activity-log-@canView','Exist:App\Models\User,user_id']],function(){
			Route::get('list/{user_id?}',['as'=>'list','uses'=>'UsersActivityLogCrud@show','middleware' => 'admin.auth:user-activity-log-@canView']);
            Route::get('data/{id?}',['as'=>'data','uses'=>'UsersActivityLogCrud@data','middleware' => 'admin.auth:user-activity-log-@canView']);
            Route::get('view/{id?}',['as'=>'view','uses'=>'UsersActivityLogCrud@view']);
			Route::get('add',['as'=>'add','uses'=>'UsersActivityLogCrud@add']);
			Route::post('insert',['as'=>'insert','uses'=>'UsersActivityLogCrud@insert']);
			Route::get('edit/{id?}',['as'=>'edit','uses'=>'UsersActivityLogCrud@edit']);
			Route::post('update',['as'=>'update','uses'=>'UsersActivityLogCrud@update']);
			Route::get('delete/{id?}',['as'=>'delete','uses'=>'UsersActivityLogCrud@delete']);
            Route::get('/{user_id?}',['uses'=>'UsersActivityLogCrud@show','middleware' => 'admin.auth:user-activity-log-@canView']);
		});
        Route::group(['prefix'=> 'support-ticket-management','namespace'=>'SupportTicket','as' => 'support-ticket-management-','middleware'=>'admin.auth:support-ticket-management'],function(){
            Route::get('view-tickets',['as'=>'view-tickets','uses'=>'Registration@getViewTickets','middleware' => 'admin.auth:view-tickets@canView']);

            Route::group(['as' => 'ticket-department-','prefix' => 'ticket-department','middleware'=>'admin.auth:ticket-department-'],function(){
                Route::get('/',['uses'=>'StDepartmentCrud@show','middleware' => 'admin.auth:ticket-department-@canView']);
          			Route::get('list',['as'=>'list','uses'=>'StDepartmentCrud@show','middleware' => 'admin.auth:ticket-department-@canView']);
          			Route::get('view/{id?}',['as'=>'view','uses'=>'StDepartmentCrud@view','middleware' => 'admin.auth:ticket-department-@canView']);
          			Route::get('add',['as'=>'add','uses'=>'StDepartmentCrud@add','middleware' => 'admin.auth:ticket-department-@canAdd']);
          			Route::post('insert',['as'=>'insert','uses'=>'StDepartmentCrud@insert','middleware' => 'admin.auth:ticket-department-@canAdd']);
          			Route::get('edit/{id?}',['as'=>'edit','uses'=>'StDepartmentCrud@edit','middleware' => 'admin.auth:ticket-department-@canModify']);
          			Route::post('update',['as'=>'update','uses'=>'StDepartmentCrud@update','middleware' => 'admin.auth:ticket-department-@canModify']);
          			Route::get('delete/{id?}',['as'=>'delete','uses'=>'StDepartmentCrud@delete','middleware' => 'admin.auth:ticket-department-@canModify']);
          		});

            Route::group(['as' => 'ticket-type-','prefix' => 'ticket-type','middleware'=>'admin.auth:ticket-type-'],function(){
                Route::get('/',['uses'=>'StTypeCrud@show','middleware' => 'admin.auth:ticket-type-@canView']);
        			Route::get('list',['as'=>'list','uses'=>'StTypeCrud@show','middleware' => 'admin.auth:ticket-type-@canView']);
        			Route::get('view/{id?}',['as'=>'view','uses'=>'StTypeCrud@view','middleware' => 'admin.auth:ticket-type-@canView']);
        			Route::get('add',['as'=>'add','uses'=>'StTypeCrud@add','middleware' => 'admin.auth:ticket-type-@canAdd']);
        			Route::post('insert',['as'=>'insert','uses'=>'StTypeCrud@insert','middleware' => 'admin.auth:ticket-type-@canAdd']);
        			Route::get('edit/{id?}',['as'=>'edit','uses'=>'StTypeCrud@edit','middleware' => 'admin.auth:ticket-type-@canModify']);
        			Route::post('update',['as'=>'update','uses'=>'StTypeCrud@update','middleware' => 'admin.auth:ticket-type-@canModify']);
        			Route::get('delete/{id?}',['as'=>'delete','uses'=>'StTypeCrud@delete','middleware' => 'admin.auth:ticket-type-@canModify']);
        		});

            Route::group(['as' => 'ticket-priority-','prefix' => 'ticket-priority','middleware'=>'admin.auth:ticket-priority-'],function(){
                Route::get('/',['uses'=>'StPriorityCrud@show','middleware' => 'admin.auth:ticket-priority-@canView']);
        			Route::get('list',['as'=>'list','uses'=>'StPriorityCrud@show','middleware' => 'admin.auth:ticket-priority-@canView']);
        			Route::get('view/{id?}',['as'=>'view','uses'=>'StPriorityCrud@view','middleware' => 'admin.auth:ticket-priority-@canView']);
        			Route::get('add',['as'=>'add','uses'=>'StPriorityCrud@add','middleware' => 'admin.auth:ticket-priority-@canAdd']);
        			Route::post('insert',['as'=>'insert','uses'=>'StPriorityCrud@insert','middleware' => 'admin.auth:ticket-priority-@canAdd']);
        			Route::get('edit/{id?}',['as'=>'edit','uses'=>'StPriorityCrud@edit','middleware' => 'admin.auth:ticket-priority-@canModify']);
        			Route::post('update',['as'=>'update','uses'=>'StPriorityCrud@update','middleware' => 'admin.auth:ticket-priority-@canModify']);
        			Route::get('delete/{id?}',['as'=>'delete','uses'=>'StPriorityCrud@delete','middleware' => 'admin.auth:ticket-priority-@canModify']);
        		});

            Route::group(['as' => 'ticket-status-type-','prefix' => 'ticket-status-type','middleware'=>'admin.auth:ticket-status-type-'],function(){
                Route::get('/',['uses'=>'StStatusTypeCrud@show','middleware' => 'admin.auth:ticket-status-type-@canView']);
                Route::get('list',['as'=>'list','uses'=>'StStatusTypeCrud@show','middleware' => 'admin.auth:ticket-status-type-@canView']);
        			Route::get('view/{id?}',['as'=>'view','uses'=>'StStatusTypeCrud@view','middleware' => 'admin.auth:ticket-status-type-@canView']);
        			Route::get('add',['as'=>'add','uses'=>'StStatusTypeCrud@add','middleware' => 'admin.auth:ticket-status-type-@canAdd']);
        			Route::post('insert',['as'=>'insert','uses'=>'StStatusTypeCrud@insert','middleware' => 'admin.auth:ticket-status-type-@canAdd']);
        			Route::get('edit/{id?}',['as'=>'edit','uses'=>'StStatusTypeCrud@edit','middleware' => 'admin.auth:ticket-status-type-@canModify']);
        			Route::post('update',['as'=>'update','uses'=>'StStatusTypeCrud@update','middleware' => 'admin.auth:ticket-status-type-@canModify']);
        			Route::get('delete/{id?}',['as'=>'delete','uses'=>'StStatusTypeCrud@delete','middleware' => 'admin.auth:ticket-status-type-@canModify']);
        		});

            Route::group(['as' => 'all-tickets-','prefix' => 'all-support-tickets','middleware'=>'admin.auth:all-tickets-'],function(){
                Route::get('/',['uses'=>'SupportTicketCrud@show','middleware' => 'admin.auth:all-tickets-@canView']);
    			Route::get('list',['as'=>'list','uses'=>'SupportTicketCrud@show','middleware' => 'admin.auth:all-tickets-@canView']);
    			Route::get('view/{id?}',['as'=>'view','uses'=>'SupportTicketCrud@view','middleware' => 'admin.auth:all-tickets-@canView']);
    			Route::get('add',['as'=>'add','uses'=>'SupportTicketCrud@add','middleware' => 'admin.auth:all-tickets-@canAdd']);
    			Route::post('insert',['as'=>'insert','uses'=>'SupportTicketCrud@insert','middleware' => 'admin.auth:all-tickets-@canAdd']);
    			Route::get('edit/{id?}',['as'=>'edit','uses'=>'SupportTicketCrud@edit','middleware' => 'admin.auth:all-tickets-@canModify']);
                Route::get('edit-ticket/{id?}',['as'=>'edit-ticket','uses'=>'SupportTicketCrud@getEditTicket','middleware' => 'admin.auth:all-tickets-@canModify']);
    			Route::post('update',['as'=>'update','uses'=>'SupportTicketCrud@update','middleware' => 'admin.auth:all-tickets-@canModify']);
    			Route::get('delete/{id?}',['as'=>'delete','uses'=>'SupportTicketCrud@delete','middleware' => 'admin.auth:all-tickets-@canModify']);
    		});
            Route::get('add-ticket',['as'=>'add-ticket','uses'=>'SupportTicketCrud@getAddTicket','middleware' => 'admin.auth:all-tickets-@canAdd']);
            Route::post('add-ticket',['as'=>'add-ticket','uses'=>'SupportTicketCrud@postAddTicket','middleware' => 'admin.auth:all-tickets-@canAdd']);
            Route::post('edit-ticket',['as'=>'edit-ticket','uses'=>'SupportTicketCrud@postEditTicket','middleware' => 'admin.auth:all-tickets-@canAdd']);
            Route::post('change-allocate-to',['as'=>'change-allocate-to','uses'=>'SupportTicketCrud@postChangeAllocateTo','middleware' => 'admin.auth:all-tickets-@canModify']);
            Route::post('change-status',['as'=>'change-status','uses'=>'SupportTicketCrud@postChangeStatus','middleware' => 'admin.auth:all-tickets-@canModify']);
            Route::post('show-message',['as'=>'show-message','uses'=>'SupportTicketCrud@postShowMessage','middleware' => 'admin.auth:all-tickets-@canView']);
            Route::post('ticket-reply',['as'=>'ticket-reply','uses'=>'SupportTicketCrud@postTicketReply','middleware' => 'admin.auth:all-tickets-@canAdd']);

            Route::group(['as' => 'my-tickets-','prefix' => 'my-tickets','middleware'=>'admin.auth:my-tickets-'],function(){
                Route::get('/',['uses'=>'MyTicketCrud@show','middleware' => 'admin.auth:my-tickets-@canView']);
    			Route::get('list',['as'=>'list','uses'=>'MyTicketCrud@show','middleware' => 'admin.auth:my-tickets-@canView']);
    			Route::get('view/{id?}',['as'=>'view','uses'=>'MyTicketCrud@view','middleware' => 'admin.auth:my-tickets-@canView']);
    			Route::get('add',['as'=>'add','uses'=>'MyTicketCrud@add','middleware' => 'admin.auth:my-tickets-@canAdd']);
    			Route::post('insert',['as'=>'insert','uses'=>'MyTicketCrud@insert','middleware' => 'admin.auth:my-tickets-@canAdd']);
    			Route::get('edit/{id?}',['as'=>'edit','uses'=>'MyTicketCrud@edit','middleware' => 'admin.auth:my-tickets-@canModify']);
                Route::get('edit-my-ticket/{id?}',['as'=>'edit-my-ticket','uses'=>'MyTicketCrud@getEditTicket','middleware' => 'admin.auth:my-tickets-@canModify']);
    			Route::post('update',['as'=>'update','uses'=>'MyTicketCrud@update','middleware' => 'admin.auth:my-tickets-@canModify']);
    			Route::get('delete/{id?}',['as'=>'delete','uses'=>'MyTicketCrud@delete','middleware' => 'admin.auth:my-tickets-@canModify']);
    		});
            Route::get('add-my-ticket',['as'=>'add-my-ticket','uses'=>'MyTicketCrud@getAddTicket','middleware' => 'admin.auth:my-tickets-@canAdd']);
            Route::post('add-my-ticket',['as'=>'add-my-ticket','uses'=>'MyTicketCrud@postAddTicket','middleware' => 'admin.auth:my-tickets-@canAdd']);
            Route::post('edit-my-ticket',['as'=>'edit-my-ticket','uses'=>'MyTicketCrud@postEditTicket','middleware' => 'admin.auth:my-tickets-@canAdd']);
            Route::post('change-allocate-to',['as'=>'change-allocate-to','uses'=>'MyTicketCrud@postChangeAllocateTo','middleware' => 'admin.auth:my-tickets-@canModify']);
            Route::post('change-status',['as'=>'change-status','uses'=>'MyTicketCrud@postChangeStatus','middleware' => 'admin.auth:my-tickets-@canModify']);
            Route::post('show-message',['as'=>'show-message','uses'=>'MyTicketCrud@postShowMessage','middleware' => 'admin.auth:my-tickets-@canView']);
            Route::post('ticket-reply',['as'=>'ticket-reply','uses'=>'MyTicketCrud@postTicketReply','middleware' => 'admin.auth:my-tickets-@canAdd']);
        });
    });
