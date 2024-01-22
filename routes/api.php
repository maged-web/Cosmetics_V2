<?php

use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Auth\ForgetPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\user\UserAuth;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// login admin
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LogoutController::class, 'logout'])->middleware(['auth:sanctum']);

// forget password
Route::post('password/forget-password', [ForgetPasswordController::class, 'forgetPassword']);
//send verification-code
Route::post('password/verification-code', [ResetPasswordController::class, 'verificationCode']);
// reset password with verification code
Route::post('password/reset-password', [ResetPasswordController::class, 'resetPassword'])->middleware(['auth:sanctum']);
// reset password with old password
Route::post('password/new-password', [ResetPasswordController::class, 'resetPasswordWithOldPassword'])->middleware(['auth:sanctum']);

// get sections
Route::get('/section', [SectionController::class, 'getSections'])->middleware(['auth:sanctum']);
// get section by id
Route::get('/section/{id?}', [SectionController::class, 'oneSection'])->middleware(['auth:sanctum']);
// add section
Route::post('/section', [SectionController::class, 'addSection'])->middleware(['auth:sanctum', 'check_admin']);
// update section
Route::post('/section/{id?}', [SectionController::class, 'updateSection'])->middleware(['auth:sanctum', 'check_admin']);
// delete section
Route::delete('/section/{id?}', [SectionController::class, 'deleteSection'])->middleware(['auth:sanctum', 'check_admin']);

// get categories
Route::get('/categories', [CategoryController::class, 'categories'])->middleware(['auth:sanctum']);
// get categories by section id
Route::get('/{section_id?}/categories', [CategoryController::class, 'sectionCategories'])->middleware(['auth:sanctum']);
// get category by id
Route::get('/categories/{id?}', [CategoryController::class, 'categoryId'])->middleware(['auth:sanctum']);
// add category
Route::post('/categories', [CategoryController::class, 'addCategory'])->middleware(['auth:sanctum', 'check_admin']);
// update category
Route::post('categories/{id?}', [CategoryController::class, 'updateCategory'])->middleware(['auth:sanctum', 'check_admin']);
// delete category
Route::delete('categories/{id?}', [CategoryController::class, 'deleteCategory'])->middleware(['auth:sanctum', 'check_admin']);

// get items
Route::get('/items', [ItemController::class, 'items'])->middleware(['auth:sanctum']);
// get items by category
Route::get('/{categoty_id?}/items', [ItemController::class, 'itemsByCategory'])->middleware(['auth:sanctum']);
// get item by id
Route::get('/items/{id?}', [ItemController::class, 'itemById'])->middleware(['auth:sanctum']);
// add item
Route::post('/items', [ItemController::class, 'addItem'])->middleware(['auth:sanctum', 'check_admin']);
// update item
Route::post('/items/{id?}', [ItemController::class, 'updateItem'])->middleware(['auth:sanctum', 'check_admin']);
// delete item
Route::delete('/items/{id?}', [ItemController::class, 'deleteItem'])->middleware(['auth:sanctum', 'check_admin']);

// get reviews
Route::get('/{items?}/reviews', [ReviewController::class, 'reviews'])->middleware(['auth:sanctum']);
// get reviews by id
Route::get('/reviews/{id?}', [ReviewController::class, 'reviewsId'])->middleware(['auth:sanctum']);
//  add review
Route::post('/reviews', [ReviewController::class, 'addReview'])->middleware(['auth:sanctum']);
// update review
Route::post('/reviews/{id?}', [ReviewController::class, 'updateReview'])->middleware(['auth:sanctum']);
// delete review
Route::delete('/reviews/{id?}', [ReviewController::class, 'deleteReview'])->middleware(['auth:sanctum']);


// api users routes
// register (user)
Route::post('/user/register', [UserAuth::class, 'register']);
// send code
Route::post('/user/code', [UserAuth::class, 'sendCode']);
// confirm code
Route::post('/user/verification', [UserAuth::class, 'verificationCode']);
// login (user)
Route::post('/user/login', [UserAuth::class, 'login']);
// edit image and username
Route::post('/user/edit/{id?}', [UserAuth::class, 'editProfile'])->middleware(['auth:sanctum']);
// edit location
Route::post('/user/location/{id?}', [UserAuth::class, 'editLocation'])->middleware(['auth:sanctum']);

// Booking
Route::get('/user/booking', [BookingController::class, 'bookings'])->middleware(['auth:sanctum']);
// add booking
Route::post('/user/booking', [BookingController::class, 'addBooking'])->middleware(['auth:sanctum']);
// update booking
Route::post('/user/booking/{id?}', [BookingController::class, 'updateBooking'])->middleware(['auth:sanctum']);
// cancel Booking
Route::get('/user/booking/cancel/{id?}', [BookingController::class, 'cancelBooking'])->middleware(['auth:sanctum']);
// receive Booking
Route::get('/user/booking/receive/{id?}', [BookingController::class, 'receiveBooking'])->middleware(['auth:sanctum']);
// get receive Bookings
Route::get('/booking/receive', [BookingController::class, 'receives'])->middleware(['auth:sanctum']);
// get cancel Bookings
Route::get('/booking/cancel', [BookingController::class, 'bookingCanceled'])->middleware(['auth:sanctum']);




// test
Route::get('/test', function () {
    // $user = auth()->user();
    // return $user;
    return "fghhfgh";
})->middleware(['auth:sanctum', 'check_admin']);

Route::post('/register', function () {
    $user = new User;
    $user->name = 'ahmed';
    $user->email = 'eslam@gmail.com';
    $user->location = null;
    $user->image = 'default_image.jpg';
    $user->password = bcrypt('12345678');
    $user->save();

    $role = new Role();
    $role->role = 'admin';
    $user->role()->save($role);

    // $user = User::find(3)->role;
    return $user;
});













Route::middleware(['auth:sanctum', 'check_admin'])->group(function () {

    Route::get('/admin/users', [App\Http\Controllers\UserController::class, 'showAllCustomers'])->name('index');/* ->middleware(['auth:sanctum', 'check_admin']); */

    Route::post('/admin/blockuser/{id}', [App\Http\Controllers\UserController::class, 'blockUser'])->name('user.block');/* ->middleware(['auth:sanctum', 'check_admin']); */
    
    Route::post('/admin/unblockuser/{id}', [App\Http\Controllers\UserController::class, 'unBlockUser'])->name('user.unblock');/* ->middleware(['auth:sanctum', 'check_admin']); */
    
    Route::get('/admin/showBlocked/', [App\Http\Controllers\UserController::class, 'showBlocked'])->name('user.showBlocked');/* ->middleware(['auth:sanctum', 'check_admin']); */


  
    Route::get('admin/showAllNotification', [App\Http\Controllers\RequestController::class, 'showAllNotification'])->name('notifications.show');/* ->middleware(['auth:sanctum', 'check_admin']); */

    Route::get('admin/showNotification/{id}', [App\Http\Controllers\RequestController::class, 'showNotification'])->name('notification.show');/* ->middleware(['auth:sanctum', 'check_admin']); */

    Route::post('admin/markAsRead', [App\Http\Controllers\RequestController::class, 'markAsRead'])->name('request.markAsRead');/* ->middleware(['auth:sanctum', 'check_admin']); */

   
   
    Route::get('/admin/messages/center', [App\Http\Controllers\MessageController::class, 'adminMessagesCenter'])->name('messages.admin');/* ->middleware(['auth:sanctum', 'check_admin']); */

    Route::get('/admin/messages/center/{userId}', [App\Http\Controllers\MessageController::class, 'showConversation']);/* ->middleware(['auth:sanctum', 'check_admin']); */

    Route::post('/admin/messages/center/{userId}', [App\Http\Controllers\MessageController::class, 'sendReply'])->name('messages.reply');/* ->middleware(['auth:sanctum', 'check_admin']); */

    Route::delete('/messages/center/delete/{msgID}', [App\Http\Controllers\MessageController::class, 'deleteMessage'])->name('messages.admin.delete');/* ->middleware(['auth:sanctum', 'check_admin']); */

    Route::post('admin/messages/center/sendVoice/{id}', [App\Http\Controllers\MessageController::class, 'sendVoiceByAdmin'])->name('admin.messages.center.send.voice');/* ->middleware(['auth:sanctum', 'check_admin']); */

    Route::post('admin/messages/center/sendPhoto/{id}', [App\Http\Controllers\MessageController::class, 'sendPhotoByAdmin'])->name('admin.messages.center.send.Photo');/* ->middleware(['auth:sanctum', 'check_admin']); */

});




Route::middleware(['auth:sanctum'])->group(function () {

    /* Route::get('/createRequest', [App\Http\Controllers\RequestController::class, 'createRequest'])->name('request.create')->middleware(['auth:sanctum']);
    */

    Route::post('/sendRequest', [App\Http\Controllers\RequestController::class, 'sendRequest'])->name('request.send');/* ->middleware(['auth:sanctum']); */

    
    
    Route::get('/messages/center', [App\Http\Controllers\MessageController::class, 'messagesCenter'])->name('messages');/* ->middleware(['auth:sanctum']); */

    Route::post('/messages/center/send', [App\Http\Controllers\MessageController::class, 'sendMessageByuser'])->name('messages.send');/* ->middleware(['auth:sanctum']); */

    Route::delete('/messages/center/delete/{msgID}', [App\Http\Controllers\MessageController::class, 'deleteMessage'])->name('messages.delete');/* ->middleware(['auth:sanctum']); */

    /* Route::get('/messages/center/update/{msgID}', [App\Http\Controllers\MessageController::class, 'editMessage'])->name('messages.update')->middleware(['auth:sanctum']);
    */

    Route::post('/messages/center/update/send/{msgID}', [App\Http\Controllers\MessageController::class, 'editMessageSend'])->name('messages.update.send');/* ->middleware(['auth:sanctum']); */

    Route::post('/messages/center/sendPhoto/', [App\Http\Controllers\MessageController::class, 'sendPhotoByUser'])->name('messages.center.send.Photo');/* ->middleware(['auth:sanctum']); */

    Route::post('/messages/center/sendVoice/', [App\Http\Controllers\MessageController::class, 'sendVoiceByUser'])->name('messages.center.send.voice');/* ->middleware(['auth:sanctum']); */

});





