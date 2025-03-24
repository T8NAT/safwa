<?php

use App\Http\Controllers\Admin\Coupon\CouponController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\SubscriberController;
use App\Providers\Admin\BasicSettingsProvider;
use Pusher\PushNotifications\PushNotifications;
use App\Http\Controllers\Admin\CookieController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\Cars\CarController;
use App\Http\Controllers\Admin\CurrencyController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\UserCareController;
use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\Admin\AdminCareController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CarBookingController;
use App\Http\Controllers\Admin\ExtensionsController;
use App\Http\Controllers\Admin\ServerInfoController;
use App\Http\Controllers\Admin\SetupEmailController;
use App\Http\Controllers\Admin\SetupPagesController;
use App\Http\Controllers\Admin\UsefulLinkController;
use App\Http\Controllers\Admin\AppSettingsController;
use App\Http\Controllers\Admin\WebSettingsController;
use App\Http\Controllers\Admin\BroadcastingController;
use App\Http\Controllers\Admin\Cars\CarAreaController;
use App\Http\Controllers\Admin\Cars\CarTypeController;
use App\Http\Controllers\Admin\SetupSectionsController;
use App\Http\Controllers\Admin\SupportTicketController;
use App\Http\Controllers\Admin\PushNotificationController;
use App\Http\Controllers\Admin\AppOnboardScreensController;
use App\Http\Controllers\Admin\SystemMaintenanceController;
use App\Http\Controllers\Admin\ServiceBookingController;
use App\Http\Controllers\Admin\Services\serviceAreaController;
use App\Http\Controllers\Admin\Services\serviceController;
use App\Http\Controllers\Admin\Services\serviceTypeController;


// All Admin Route Is Here
Route::prefix('admin')->name('admin.')->group(function () {

    // Dashboard Section
    Route::controller(DashboardController::class)->group(function () {
        Route::get('dashboard', 'index')->name('dashboard');
        Route::post('logout', 'logout')->name('logout');
        Route::post('notifications/clear','notificationsClear')->name('notifications.clear');
    });

    // Admin Profile
    Route::controller(ProfileController::class)->prefix('profile')->name('profile.')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('change-password', 'changePassword')->name('change.password');
        Route::put('change-password', 'updatePassword')->name('change.password.update');
        Route::put('update', 'update')->name('update');
    });
    // Setup Currency Section
    Route::controller(CurrencyController::class)->prefix('currency')->name('currency.')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::post('store', 'store')->name('store');
        Route::put('status/update', 'statusUpdate')->name('status.update');
        Route::put('update', 'update')->name('update');
        Route::delete('delete', 'delete')->name('delete');
        Route::post('search', 'search')->name("search");
    });
    //Contact Message Section
    Route::controller(ContactMessageController::class)->prefix('contact')->name('contact.')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('details', 'messageDetails')->name('details');
        Route::delete('message/delete','messageDelete')->name('delete');
        Route::post('reply', 'reply')->name('reply');
    });
    Route::controller(SubscriberController::class)->prefix('subscriber')->name('subscriber.')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::post('reply', 'reply')->name('reply');
    });

    // Cars Section

    // car types Section
    Route::controller(CarTypeController::class)->prefix('car-types')->name('car.types.')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::post('store', 'store')->name('store');
        Route::put('status/update','statusUpdate')->name('status.update');
        Route::put('update','update')->name('update');
        Route::delete('delete','delete')->name('delete');
    });

    //car area section
    Route::controller(CarAreaController::class)->prefix('car-area')->name('car.area.')->group(function(){
        Route::get('index', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('store', 'store')->name('store');
        Route::get('edit/{id}','edit')->name('edit');
        Route::put('update/{id}','update')->name('update');
        Route::put('status/update','statusUpdate')->name('status.update');
        Route::delete('delete','delete')->name('delete');
    });

    //car section
    Route::controller(CarController::class)->prefix('car')->name('car.')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('store', 'store')->name('store');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::put('status/update', 'statusUpdate')->name('status.update');
        Route::put('update/{id}', 'update')->name('update');
        Route::delete('delete', 'delete')->name('delete');
        Route::post('get/area/types', 'getAreaTypes')->name('get.area.types');
    });

    //car booking section
    Route::controller(CarBookingController::class)->prefix('booking')->name('booking.')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::post('reply-message', 'reply')->name('messages.reply');
        Route::get('booking-details/{slug}', 'bookingDetails')->name('details');
        Route::post('status', 'updateStatus')->name('status');
        Route::post('fare', 'fareCalculate')->name('fare');
    });


     // Services Section

    // service types Section
    Route::controller(serviceTypeController::class)->prefix('service-types')->name('service.types.')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::post('store', 'store')->name('store');
        Route::put('status/update','statusUpdate')->name('status.update');
        Route::put('update','update')->name('update');
        Route::delete('delete','delete')->name('delete');
    });

//    service area section
     Route::controller(serviceAreaController::class)->prefix('service-area')->name('service.area.')->group(function(){
         Route::get('index', 'index')->name('index');
         Route::get('create', 'create')->name('create');
         Route::post('store', 'store')->name('store');
         Route::get('edit/{id}','edit')->name('edit');
         Route::put('update/{id}','update')->name('update');
         Route::put('status/update','statusUpdate')->name('status.update');
         Route::delete('delete','delete')->name('delete');
     });

//    service section
     Route::controller(serviceController::class)->prefix('service')->name('service.')->group(function () {
         Route::get('index', 'index')->name('index');
         Route::get('create', 'create')->name('create');
         Route::post('store', 'store')->name('store');
         Route::get('edit/{id}', 'edit')->name('edit');
         Route::put('status/update', 'statusUpdate')->name('status.update');
         Route::put('update/{id}', 'update')->name('update');
         Route::delete('delete', 'delete')->name('delete');
         Route::post('get/area/types', 'getAreaTypes')->name('get.area.types');
     });

    //service booking section
     Route::controller(ServiceBookingController::class)->prefix('service-booking')->name('service.booking.')->group(function () {
         Route::get('index', 'index')->name('index');
         Route::post('reply-message', 'reply')->name('messages.reply');
         Route::get('booking-details/{slug}', 'bookingDetails')->name('details');
         Route::post('status', 'updateStatus')->name('status');
         Route::post('fare', 'fareCalculate')->name('fare');
     });

    // Coupons Section

    // coupon Section
    Route::controller(CouponController::class)->prefix('coupon')->name('coupon.')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('store', 'store')->name('store');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::put('status/update', 'statusUpdate')->name('status.update');
        Route::put('update/{id}', 'update')->name('update');
        Route::delete('delete', 'delete')->name('delete');
    });


    // User Care Section
    Route::controller(UserCareController::class)->prefix('users')->name('users.')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('active', 'active')->name('active');
        Route::get('banned', 'banned')->name('banned');
        Route::get('email/unverified', 'emailUnverified')->name('email.unverified');
        Route::get('email-user', 'emailAllUsers')->name('email.users');
        Route::post('email-users/send', 'sendMailUsers')->name('email.users.send')->middleware("mail");
        Route::get('details/{username}', 'userDetails')->name('details');
        Route::post('details/update/{username}', 'userDetailsUpdate')->name('details.update');
        Route::get('login/logs/{username}', 'loginLogs')->name('login.logs');
        Route::get('mail/logs/{username}', 'mailLogs')->name('mail.logs');
        Route::post('send/mail/{username}', 'sendMail')->name('send.mail')->middleware("mail");
        Route::post('login-as-member/{username?}','loginAsMember')->name('login.as.member');
        Route::post('search','search')->name('search');
    });


    // Admin Care Section
    Route::controller(AdminCareController::class)->prefix('admins')->name('admins.')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('email-admin', 'emailAllAdmins')->name('email.admins');
        Route::delete('admin/delete','deleteAdmin')->name('admin.delete')->middleware('admin.delete.guard');
        Route::post('send/email','sendEmail')->name('send.email')->middleware("mail");
        Route::post('admin/search','adminSearch')->name('search');

        Route::post("store","store")->name("admin.store");
        Route::put("update","update")->name("admin.update");
        Route::put('status/update','statusUpdate')->name('admin.status.update');

        Route::get('role/index','roleIndex')->name('role.index');
        Route::post('role/store','roleStore')->name('role.store');
        Route::put('role/update','roleUpdate')->name('role.update');
        Route::delete('role/remove','roleRemove')->name('role.delete')->middleware('admin.role.delete.guard');

        Route::get('role/permission/index','rolePermissionIndex')->name('role.permission.index');
        Route::post('role/permission/store','rolePermissionStore')->name('role.permission.store');
        Route::put('role/permission/update','rolePermissionUpdate')->name('role.permission.update');
        Route::delete('role/permission/delete','rolePermissionDelete')->name('role.permission.dalete');
        Route::delete('role/permission/assign/delete/{slug}','rolePermissionAssignDelete')->name('role.permission.assign.delete');

        Route::get('role/permission/{slug}','viewRolePermission')->name('role.permission');
        Route::post('role/permission/assign/{slug}','rolePermissionAssign')->name('role.permission.assign');
    });


    // Web Settings Section
    Route::controller(WebSettingsController::class)->prefix('web-settings')->name('web.settings.')->group(function(){
        Route::get('basic-settings','basicSettings')->name('basic.settings');
        Route::put('basic-settings/update','basicSettingsUpdate')->name('basic.settings.update');
        Route::put('basic-settings/activation/update','basicSettingsActivationUpdate')->name('basic.settings.activation.update');
        Route::get('image-assets','imageAssets')->name('image.assets');
        Route::put('image-assets/update','imageAssetsUpdate')->name('image.assets.update');
        Route::get('setup-seo','setupSeo')->name('setup.seo');
        Route::put('setup-seo/update','setupSeoUpdate')->name('setup.seo.update');
    });


    // App Settings Section
    Route::prefix('app-settings')->name('app.settings.')->group(function () {
        Route::controller(AppSettingsController::class)->group(function () {
            Route::get('splash-screen', 'splashScreen')->name('splash.screen');
            Route::put('splash-screen/update', 'splashScreenUpdate')->name('splash.screen.update');
            Route::get('urls', 'urls')->name('urls');
            Route::put('urls/update', 'urlsUpdate')->name('urls.update');
        });

        Route::controller(AppOnboardScreensController::class)->name('onboard.')->group(function () {
            Route::get('onboard-screens', 'onboardScreens')->name('screens');
            Route::post('onboard-screens/store', 'onboardScreenStore')->name('screen.store');
            Route::put('onboard-screen/update', 'onboardScreenUpdate')->name('screen.update');
            Route::put('onboard-screen/status/update', 'onboardScreenStatusUpdate')->name('screen.status.update');
            Route::delete('onboard-screen/delete','onboardScreenDelete')->name('screen.delete');
        });
    });


    // Language Section
    Route::controller(LanguageController::class)->prefix('languages')->name('languages.')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::post('store','store')->name('store');
        Route::put('update','update')->name('update');
        Route::put('status/update','statusUpdate')->name('status.update');
        Route::get('info/{code}','info')->name('info');
        Route::post('import','import')->name('import');
        Route::delete('delete','delete')->name('delete');
        Route::post('switch','switch')->name('switch');
        Route::get('download','download')->name('download');
    });

    // System Maintenance
    Route::controller(SystemMaintenanceController::class)->prefix('system-maintenance')->name('system.maintenance.')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::put('update', 'update')->name('update');
    });

    // Setup Email Section
    Route::controller(SetupEmailController::class)->prefix('setup-email')->name('setup.email.')->group(function () {
        Route::get('config', 'configuration')->name('config');
        // Route::get('template/default', 'defaultTemplate')->name('template.default');
        Route::put('config/update', 'update')->name('config.update');
        Route::post('test-mail/send','sendTestMail')->name('test.mail.send')->middleware('mail');
    });

    // Setup Section
    Route::controller(SetupSectionsController::class)->prefix('setup-sections')->name('setup.sections.')->group(function () {
        Route::get('{slug}', 'sectionView')->name('section');
        Route::post('update/{slug}','sectionUpdate')->name('section.update');
        Route::post('item/store/{slug}','sectionItemStore')->name('section.item.store');
        Route::post('item/update/{slug}','sectionItemUpdate')->name('section.item.update');
        Route::delete('item/delete/{slug}','sectionItemDelete')->name('section.item.delete');
        //Setup Announcement Category Type
        Route::post('category/store', 'storeCategory')->name('category.store');
        Route::put('category/status/update','categoryStatusUpdate')->name('category.status.update');
        Route::put('category/update', 'categoryUpdate')->name('category.update');
        Route::delete('category/delete','categoryDelete')->name('category.delete');
        Route::post('category/search','categorySearch')->name("category.search");
        //Setup Announcement Section
        Route::post('announcement/store', 'announcementItemStore')->name('announcement.store');
        Route::put('announcement/status/update','announcementStatusUpdate')->name('announcement.status.update');
        Route::get('announcement/edit/{id}','announcementEdit')->name('announcement.edit');
        Route::put('announcement/update', 'announcementItemUpdate')->name('announcement.update');
        Route::delete('announcement/delete','announcementItemDelete')->name('announcement.delete');
    });

    // Setup Pages Controller
    Route::controller(SetupPagesController::class)->prefix('setup-pages')->name('setup.pages.')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::put('status/update','statusUpdate')->name('status.update');
    });

    // Useful Links Controller
    Route::controller(UsefulLinkController::class)->prefix('useful-links')->name('useful.links.')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::post('store', 'store')->name('store');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('update', 'update')->name('update');
        Route::put('status/update','statusUpdate')->name('status.update');
        Route::delete('delete','delete')->name('delete');
    });

    // Push Notification Setup Section
    Route::controller(PushNotificationController::class)->prefix('push-notification')->name('push.notification.')->group(function(){
        Route::get('config','configuration')->name('config');
        Route::put('update','update')->name('update');

        Route::get('/','index')->name('index');
        Route::post('send','send')->name('send');
    });


    // Broadcasting Setup Section
    Route::controller(BroadcastingController::class)->prefix('broadcast')->name('broadcast.')->group(function(){
        Route::put("config/update","configUpdate")->name('config.update');
    });


    //  GDPR Cookie Section
    Route::controller(CookieController::class)->prefix('cookie')->name('cookie.')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::put('update', 'update')->name('update');
    });

    // Server Info Section
    Route::controller(ServerInfoController::class)->prefix('server-info')->name('server.info.')->group(function () {
        Route::get('index', 'index')->name('index');
    });

    // Support Ticked Section
    Route::controller(SupportTicketController::class)->prefix('support-ticket')->name('support.ticket.')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('active', 'active')->name('active');
        Route::get('pending', 'pending')->name('pending');
        Route::get('solved', 'solved')->name('solved');
        Route::get('conversation/{ticket_id}', 'conversation')->name('conversation');
        Route::post('message/reply','messageReply')->name('message.reply');
        Route::post('solve','solve')->name('solve');
    });

    // Extension Section
    Route::controller(ExtensionsController::class)->prefix('extension')->name('extension.')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::post('update/{id}', 'update')->name('update');
        Route::put('status/update', 'statusUpdate')->name('status.update');
    });

    // Cache Clear Section
    Route::get('cache/clear', function () {
        Artisan::all('cache:clear');
        Artisan::all('route:clear');
        Artisan::all('view:clear');
        Artisan::all('config:clear');
        return redirect()->back()->with(['success' => ['Cache Clear Successfully!']]);
    })->name('cache.clear');
});

    Route::get('pusher/beams-auth', function (Request $request) {
    if(Auth::check() == false) {
        return response(['Inconsistent request'], 401);
    }
    $userID = Auth::user()->id;

    $basic_settings = BasicSettingsProvider::get();
    if(!$basic_settings) {
        return response('Basic setting not found!', 404);
    }

    $notification_config = $basic_settings->push_notification_config;

    if(!$notification_config) {
        return response('Notification configuration not found!', 404);
    }

    $instance_id    = $notification_config->instance_id ?? null;
    $primary_key    = $notification_config->primary_key ?? null;
    if($instance_id == null || $primary_key == null) {
        return response('Sorry! You have to configure first to send push notification.', 404);
    }
    $beamsClient = new PushNotifications(
        array(
            "instanceId" => $notification_config->instance_id,
            "secretKey" => $notification_config->primary_key,
        )
    );
    $publisherUserId = "admin-".$userID;
    try{
        $beamsToken = $beamsClient->generateToken($publisherUserId);
    }catch(Exception $e) {
        return response(['Server Error. Failed to generate beams token.'], 500);
    }

    return response()->json($beamsToken);
})->name('pusher.beams.auth');
