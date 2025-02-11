<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\FactoryController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\ThreeController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\EventController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('admin')->middleware('throttle:5000,1')->group(function () {
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('/', [AdminAuthController::class, 'index']);
    Route::get('/{id}', [AdminAuthController::class, 'show']);
});
Route::prefix('admin')->middleware(['auth:sanctum','super_admin','throttle:5000,1'])->group(function () {
    Route::post('/register', [AdminAuthController::class, 'register']);
    Route::post('/{id}', [AdminAuthController::class, 'update']);
    Route::delete('/{id}', [AdminAuthController::class, 'destroy']);
    Route::post('/{id}/change-email', [AdminAuthController::class, 'updateEmail'])->name('user.change-email');
    Route::post('/{id}/change-email-address', [AdminAuthController::class, 'updateEmailAddress'])->name('user.change-email-address');
});

//Blogs Routes
Route::prefix('blogs')->middleware('throttle:5000,1')->group(function () {
    Route::get('/', [BlogController::class, 'getBlogs']);
    Route::get('/tags', [BlogController::class, 'getBlogsWithTags']);
    Route::get('/type', [BlogController::class, 'getBlogByType']);
    Route::get('/best', [BlogController::class, 'getBlogByBest']);    
    Route::get('/{id}', [BlogController::class, 'getBlogByID']);
});
Route::prefix('blogs')->middleware(['auth:sanctum','throttle:5000,1'])->group(function () {
    Route::post('/',[BlogController::class,'insertBlog'])->middleware('admin:adding');
    Route::post('/solution/{id}',[BlogController::class,'insertSolution'])->middleware('admin:adding');
    Route::delete('/{id}', [BlogController::class, 'deleteBlog'])->middleware('admin:deleting');
    Route::post('/{id}', [BlogController::class, 'updateBlog'])->middleware('admin:editing');
    Route::post('/tags/create/{id}', [BlogController::class, 'updateTagBlog'])->middleware('admin:editing');
    Route::post('/tags/delete/{id}', [BlogController::class, 'deleteTagBlog'])->middleware('admin:editing');
    Route::post('/swap/order', [BlogController::class, 'swapBlogsQueue'])->middleware('admin:editing');
});


// Sites Routes
Route::prefix('sites')->middleware('throttle:5000,1')->group(function () {
    Route::get('/', [SiteController::class, 'index']);
    Route::get('/six', [SiteController::class, 'getSixSites']);
    Route::get('/{id}', [SiteController::class, 'show']);
});
Route::prefix('sites')->middleware(['auth:sanctum','throttle:5000,1'])->group(function () {
    Route::post('/',[SiteController::class,'store'])->middleware('admin:adding');
    Route::delete('/{id}', [SiteController::class, 'destroy'])->middleware('admin:deleting');
    Route::post('/swap/order', [SiteController::class, 'swapSitesQueue'])->middleware('admin:editing');
    Route::post('/{id}', [SiteController::class, 'update'])->middleware('admin:editing');
});


// Equipments Routes
Route::prefix('equipments')->middleware('throttle:5000,1')->group(function () {
    Route::get('/', [EquipmentController::class, 'getEquipments']);
    Route::get('/type', [EquipmentController::class, 'getEquipmentsByType']);
    Route::get('/{id}', [EquipmentController::class, 'getEquipmentByID']);
});
Route::prefix('equipments')->middleware(['auth:sanctum','throttle:5000,1'])->group(function () {
    Route::post('/',[EquipmentController::class,'insertEquipment'])->middleware('admin:adding');
    Route::delete('/{id}', [EquipmentController::class, 'deleteEquipment'])->middleware('admin:deleting');
    Route::post('/{id}', [EquipmentController::class, 'updateEquipment'])->middleware('admin:editing');
});


// Reviews Routes
Route::prefix('reviews')->middleware('throttle:5000,1')->group(function () {
    Route::get('/', [ReviewController::class, 'getReviews']);
    Route::get('/reviewsBytype', [ReviewController::class, 'getReviewsBytype']);
    Route::get('/{id}', [ReviewController::class, 'getReviewByID']);
});
Route::prefix('reviews')->middleware(['auth:sanctum','throttle:5000,1'])->group(function () {
    Route::post('/',[ReviewController::class,'createReview'])->middleware('admin:adding');
    Route::delete('/{id}', [ReviewController::class, 'deleteReview'])->middleware('admin:deleting');
    Route::post('/{id}', [ReviewController::class, 'updateReview'])->middleware('admin:editing');
    Route::post('/swap/order', [ReviewController::class, 'swapReviewQueue'])->middleware('admin:editing');
});


// Factories Routes
Route::prefix('factorys')->middleware('throttle:5000,1')->group(function () {
    Route::get('/', [FactoryController::class, 'index']);
    Route::get('/top', [FactoryController::class, 'top']);
    Route::get('/{id}', [FactoryController::class, 'show']);
});
Route::prefix('factorys')->middleware(['auth:sanctum','throttle:5000,1'])->group(function () {
    Route::post('/',[FactoryController::class,'store'])->middleware('admin:adding');
    Route::post('/{id}', [FactoryController::class, 'update'])->middleware('admin:editing');
    Route::post('/swap/order', [FactoryController::class, 'swapFactoryQueue'])->middleware('admin:editing');
    Route::delete('/{id}', [FactoryController::class, 'destroy'])->middleware('admin:deleting');
});

//Event Routes
Route::prefix('events')->middleware('throttle:5000,1')->group(function () {
    Route::get('/', [EventController::class, 'index']);
    Route::get('/top', [EventController::class, 'top']);
    Route::get('/{id}', [EventController::class, 'show']);
});
Route::prefix('events')->middleware(['auth:sanctum','throttle:5000,1'])->group(function () {
    Route::post('/',[EventController::class,'store'])->middleware('admin:adding');
    Route::post('/{id}', [EventController::class, 'update'])->middleware('admin:editing');
    Route::delete('/{id}', [EventController::class, 'destroy'])->middleware('admin:deleting');
});

// Threes Routes
Route::prefix('threes')->middleware('throttle:5000,1')->group(function () {
    Route::get('/', [ThreeController::class, 'index']);
    Route::get('/{id}', [ThreeController::class, 'show']);
});
Route::prefix('threes')->middleware(['auth:sanctum','throttle:5000,1'])->group(function () {
    Route::post('/',[ThreeController::class,'store'])->middleware('admin:adding');
    Route::post('/{id}', [ThreeController::class, 'update'])->middleware('admin:editing');
    Route::post('/swap/order', [ThreeController::class, 'swapThreeQueue'])->middleware('admin:editing');
    Route::delete('/{id}', [ThreeController::class, 'destroy'])->middleware('admin:deleting');
});


// Participant Routes
Route::prefix('participant')->middleware('throttle:5000,1')->group(function () {
    Route::get('/', [ParticipantController::class, 'index']);
    Route::get('/showparticipant', [ParticipantController::class, 'indexNum']);
    Route::get('/{id}', [ParticipantController::class, 'show']);
});
Route::prefix('participant')->middleware(['auth:sanctum','throttle:5000,1'])->group(function () {
    Route::post('/',[ParticipantController::class,'store'])->middleware('admin:adding');
    Route::post('/{id}', [ParticipantController::class, 'update'])->middleware('admin:editing');
    Route::post('/swap/order', [ParticipantController::class, 'swapParticipantQueue'])->middleware('admin:editing');
    Route::delete('/{id}', [ParticipantController::class, 'destroy'])->middleware('admin:deleting');
});


// Rental Routes
Route::prefix('rental')->middleware('throttle:5000,1')->group(function () {
    Route::get('/', [RentalController::class, 'index']);
    Route::get('/{id}', [RentalController::class, 'show']);
});
Route::prefix('rental')->middleware(['auth:sanctum','throttle:5000,1'])->group(function () {
    Route::post('/',[RentalController::class,'store'])->middleware('admin:adding');
});


// Team Routes
Route::prefix('team')->middleware('throttle:5000,1')->group(function () {
    Route::get('/', [TeamController::class, 'getTeam']);
});
Route::prefix('team')->middleware(['auth:sanctum','throttle:5000,1'])->group(function () {
    Route::post('/', [TeamController::class, 'createOrUpdateTeam'])->middleware('admin:editing');
    Route::post('/swap/order', [TeamController::class, 'swapTeamQueue'])->middleware('admin:editing');
});

//Search Route
Route::prefix('getSearchData')->middleware('throttle:5000,1')->group(function () {
    Route::post('/', [SearchController::class, 'searchData']);
});

// Send Email
Route::prefix('sendEmail')->middleware('throttle:5000,1')->group(function () {
    Route::post('/', [ContactController::class, 'sendEmail']);
});

// ['auth:sanctum','admin','throttle:5000,1']