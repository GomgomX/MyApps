<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\GuildController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\LibraryController;
use App\http\Controllers\SignatureController;
use App\Http\Controllers\LostAccountController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\EmailVerificationController;

/* 
- We can use the following chained methods with routes: middleware('auth'), name('login'), response(null, 409) & ( where('wildcard', '.*'), where('wildcard', '[A-Za-z]+') or where('wildcard', '[0-9]+') )
- If we want to find a row by a spacific field instead of the primary key (which is the id field by default) in the database through route model binding, we can simply pass the field name along with the fildcard
-> {listing:name}
- It was advised to pass the model name as a wildcard not just a variable to use route model binding functionality but that wasn't approved to be true

- If we intend to store sensitive data in sessions, we will need to consider editing session configuration " config/session.php " (some of the fields are already defined in .env file)
- secure => true: Ensures cookies are only sent over HTTPS
- http_only => true: Prevents JavaScript from accessing the session cookie, mitigating XSS attacks
- same_site => 'lax' or 'strict': Prevents the browser from sending the cookie along with cross-site requests, reducing CSRF attacks
- Some 
*/

Route::get('/', [NewsController::class, 'latest']);
Route::match(['get', 'post'], 'news/latestnews', [NewsController::class, 'latest']);

Route::prefix('account')->group(function () {
    Route::prefix('accountmanagement')->group(function () {
        Route::controller(AccountController::class)->group(function () {
            Route::match(['get', 'post'], '/', 'accountManagement')->name('login');
            Route::post('/login', 'login')->middleware('custom.guest:account');
            Route::get('/logoutaccount', 'logoutAccount');
            Route::match(['get', 'post'], '/logout', 'logout');
            Route::match(['get', 'post'], '/createcharacter', 'createCharacter')->middleware('auth:account', 'verified');
            Route::post('/storecharacter', 'storeCharacter')->middleware('auth:account');
            Route::match(['get', 'post'], '/changepassword', 'changePassword')->middleware('auth:account', 'verified');
            Route::put('/updatepassword', 'updatePassword')->middleware('auth:account');
            Route::match(['get', 'post'], '/deletecharacter', 'deleteCharacter')->middleware('auth:account');
            Route::delete('/markdeleted', 'markDeleted')->middleware('auth:account');
            Route::get('/undelete/{name}', 'undelete')->middleware('auth:account');
            Route::match(['get', 'post'] , '/registeraccount', 'registeraccount')->middleware('auth:account', 'verified');
            Route::post('/generatekey', 'generateKey')->middleware('auth:account');
            Route::match(['get', 'post'], '/changeinfo', 'changeInfo')->middleware('auth:account', 'verified');
            Route::put('/saveinfo', 'saveInfo')->middleware('auth:account');
            Route::match(['get', 'post'], '/editcharacter/{name}', 'editCharacter')->middleware('auth:account');
            Route::put('/savecharacter', 'saveCharacter')->middleware('auth:account');
        });

        Route::controller(EmailVerificationController::class)->middleware(['auth:account'])->group(function () {
            Route::get('/verification', 'show')->name('verification.notice');
            Route::get('/verify/{id}/{email}', 'verify')->name('verification.verify')->middleware(['signed', 'throttle:3,1']);
            Route::post('/verification/resend', 'resend')->name('verification.resend')->middleware('throttle:2,1');
        });    
    });

    Route::prefix('lostaccount')->controller(LostAccountController::class)->middleware(['custom.guest:account'])->group(function () {
        Route::match(['get', 'post'], '/', 'show');
        Route::post('/resetpassword', 'resetPassword');
        Route::get('/recoverykey', 'recoveryKey');
        Route::post('/checkrecoverykey', 'checkRecoveryKey');
        Route::get('/changepassword', 'changePassword');
        Route::put('/updatepassword', 'updatePassword');
        Route::get('/emailaddress', 'emailAddress')->name('password.request');
        Route::post('/sendresetlink', 'sendResetLink')->name('password.email');
        Route::get('/validatetoken/{token}', 'validateToken')->name('password.reset');
        Route::put('/updatepasswordbyemail', 'updatePasswordByEmail')->name('password.update');
    });

    Route::get('/createaccount', [AccountController::class, 'createAccount']);
    Route::post('/createaccount/storeaccount', [AccountController::class, 'storeAccount']);

    Route::get('/serverrules', [AccountController::class, 'serverRules']);
});

Route::prefix('community')->group(function () {
    Route::controller(PlayerController::class)->group(function () {
        Route::get('/characters', 'characters');
        Route::post('/characters', 'search');
        Route::get('/characters/{name}', 'show');
        Route::get('/whoisonline', 'online')->name('whoisonline');
        Route::get('/highscores', 'highscores')->name('highscores');
        Route::get('/latestdeaths', 'latestDeaths');
    });
    
    Route::get('/signature/{name}', [SignatureController::class, 'signature']);

    Route::prefix('guilds')->controller(GuildController::class)->group(function () {
        Route::match(['get', 'post'], '/', 'index');

        Route::middleware(['auth:account'])->group(function () {
            Route::get('/create', 'create');
            Route::post('/storeguild', 'store');
            Route::match(['get', 'post'], '/changerank/{id}', 'changeRank');
            Route::put('/updaterank', 'updateRank');
            Route::match(['get', 'post'], '/cancelinvite/{id}/{name}', 'cancelInvite');
            Route::delete('/deleteinvite', 'deleteInvite');
            Route::match(['get', 'post'], '/invite/{id}', 'invite');
            Route::post('/storeinvite', 'storeInvite');
            Route::match(['get', 'post'], '/accept/{id}', 'accept');
            Route::put('/joinguild', 'joinGuild');
            Route::match(['get', 'post'], '/kick/{id}/{name}', 'kick');
            Route::put('/disjoin', 'disjoin');
            Route::match(['get', 'post'], '/leave/{id}', 'leave');
            Route::put('/leaveguild', 'leaveGuild');
            Route::get('/deletebyadmin/{id}', 'deleteByAdmin');
            Route::delete('/deleteguildbyadmin', 'deleteGuildByAdmin');
            Route::patch('/changenick', 'changeNick');
            Route::match(['get', 'post'], '/manage/{id}', 'manage');
            Route::get('/changelogo/{id}', 'changeLogo');
            Route::patch('/savelogo', 'savelogo');
            Route::post('/addrank', 'addRank');
            Route::get('/deleterank/{id}/{rank}', 'deleteRank');
            Route::put('/saveranks', 'saveRanks');
            Route::get('/delete/{id}', 'delete');
            Route::delete('/deleteguild', 'deleteGuild');
            Route::get('/passleadership/{id}' ,'passLeadership');
            Route::patch('/leadershippassed', 'leadershipPassed');
            Route::get('/changemotd/{id}', 'changeMotd');
            Route::patch('/savemotd', 'savemotd');
            Route::get('/changedescription/{id}', 'changeDescription');
            Route::patch('/savedescription', 'saveDescription');
        });

        Route::match(['get', 'post'], '/{id}', 'show');
    });
});

Route::prefix('forum/communityboards')->controller(ForumController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/board/{id}', 'showBoard')->name('forum.board');
    Route::get('/thread/{id}', 'showThread')->name('forum.thread');

    Route::middleware(['auth:account'])->group(function () {
        Route::get('/newtopic/{id}', 'newTopic');
        Route::post('/addnewtopic', 'storeNewTopic');
        Route::get('/newpost/{id}', 'newPost')->name('forum.newpost');
        Route::post('/addnewpost', 'storeNewPost');
        Route::match(['get', 'post'], '/edit/{id}', 'edit');
        Route::put('/save', 'save');
        Route::get('/remove/{id}', 'deletePost');
    });
});

Route::prefix('shop')->group(function () {
    Route::middleware(['auth:account', 'verified'])->group(function () {
        Route::controller(ShopController::class)->group(function () {
            Route::prefix('shopoffer')->group(function () {
                Route::match(['get', 'post'], '/', 'shopOffer');
                Route::get('/item', 'itemOffers');
                Route::get('/mage', 'mageOffers');
                Route::get('/paladin', 'paladinOffers');
                Route::get('/knight', 'knightOffers');
                Route::get('/weapon', 'weaponOffers');
                Route::get('/shield', 'shieldOffers');
                Route::get('/container', 'containerOffers');
                Route::post('/buy', 'buyOffer');
                Route::post('/select', 'selectPlayer');
                Route::get('/confirm', 'confirmTransaction');
                Route::post('/additem', 'addItem');
            });

            Route::get('/transactionhistory', 'transactionHistory');
            Route::match(['get', 'post'], '/buypoints', 'buyPoints');
            Route::get('/buypoints/paypal', 'paypal');
            Route::match(['get', 'post'], '/buypoints/stripe', 'stripe');
            Route::get('/buypoints/stripe/success', 'stripeSuccess')->name('stripe-success');
            Route::get('/buypoints/stripe/pointsadded', 'addedViaWebhook')->name('added.via.webhook');
            Route::get('/buypoints/stripe/cancel', 'stripeCancel')->name('stripe-cancel');
        });

        Route::post('/buypoints/stripe/createsession', [TransactionController::class, 'createStripeSession'])->name('stripe-checkout-session');
    });
        
    Route::post('/buypoints/paypal/ipn', [TransactionController::class, 'paypalAddPoints']);
    Route::post('/buypoints/stripe/webhook', [TransactionController::class, 'stripeWebhook']);
});

Route::prefix('library')->controller(LibraryController::class)->group(function () {
    Route::get('/experiencetable', 'experiencetable');
});

Route::prefix('ajax')->controller(AjaxController::class)->group(function () {
    Route::post('/players', 'getPlayers');
    Route::post('/visitors', 'getVisitors');
    Route::post('/checkaccount', 'checkAccount');
    Route::post('/checkemail', 'checkEmail');
    Route::post('/checkname', 'checkName');
});

//Route::fallback([Controller::class, 'function']); // This will be executed when no other route matches the incoming request. It has to be the last route registered
/* 
-We can place two routes with the same URL since the second one will inherent from the first one as long as they have different request method
-> Route::get('posts'), [PlayerController::class, 'threads'])->name('threads');
-> Route::post('posts'), [PlayerController::class, 'store']); // This one will inherent from the first one since they gave the same route with different request method
*/

/*
// This query lists all fields with DELETE on CASCADE
SELECT 
  r.CONSTRAINT_NAME,
  r.DELETE_RULE, 
  r.TABLE_NAME,
  GROUP_CONCAT(k.COLUMN_NAME SEPARATOR ', ') AS `constraint columns`,
  r.REFERENCED_TABLE_NAME
FROM information_schema.REFERENTIAL_CONSTRAINTS r
  JOIN information_schema.KEY_COLUMN_USAGE k
  USING (CONSTRAINT_CATALOG, CONSTRAINT_SCHEMA, CONSTRAINT_NAME)
GROUP BY r.CONSTRAINT_CATALOG,
         r.CONSTRAINT_SCHEMA,
         r.CONSTRAINT_NAME
*/