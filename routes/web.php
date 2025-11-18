<?php

use App\Http\Controllers\CinemaController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\isStaff;

// Route::get('/', function () {
//     return view('home');
// })->name('home');

Route::get('/', [MovieController::class, 'home'])->name('home');
Route::get('/movies/all', [MovieController::class, 'homeAllMovie'])->name('home.movies.all');

//tidak memerlukan data: route-view
//memerlukan data: route-controller-model-controller-view

Route::get('/schedules/{movie_id}', [MovieController::class, 'movieSchedules'])->name('schedules.detail');

//daftar bioskop
Route::get('/cinemas/list', [CinemaController::class, 'listCinema'])->name('cinemas.list');
Route::get('cinemas/{cinema_id}/schedules', [CinemaController::class, 'cinemaSchedules'])->name('cinemas.schedules');
Route::middleware('isUser')->group(function () {
    //halaman pilih kursi
    Route::get(
        'schedules/{scheduleId}/hours/{hourId}/show_seats',
        [TicketController::class, 'showSeats']
    )->name('schedules.show_seats');
    Route::prefix('/tickets')->name('tickets.')->group(function() {
        Route::get('/', [TicketController::class, 'index'])->name('index');
        Route::post('/', [TicketController::class, 'store'])->name('store');
        Route::get('/{ticketId}/order', [TicketController::class, 'ticketOrder'])->name('order');
        Route::post('/{ticketId}/barcode', [TicketController::class, 'createBarcode'])->name('barcode');
        Route::get('/{ticketId}/payment', [TicketController::class, 'paymentPage'])->name('payment');
        Route::patch('/{ticketId}/payment/proof' , [TicketController::class, 'proofPayment'])->name('payment.proof');
        Route::get('/{ticketId}', [TicketController::class, 'show'])->name('show');
        Route::get('/{ticketId}/export/pdf', [TicketController::class, 'exportPdf'])->name('export.pdf');
    });
});

Route::get('/signup', function () {
    return view('signup');
})->name('signup')->middleware('isGuest');

Route::get('/login', function () {
    return view('login');
})->name('login')->middleware('isGuest');


//http method
//1. Get = menampilkan halaman/mengambil data
//2. post = menambah data
//3. patch/put = mengubah data
//4. delete = menghapus data

Route::post('/signup', [UserController::class, 'store'])->name('signup.store')
    ->middleware('isGuest');
Route::post('/login', [UserController::class, 'login'])->name('login.auth')
    ->middleware('isGuest');
Route::get('/logout', [UserController::class, 'logout'])->name('logout');

//prefix : awalan, semua route yanng ada di group prefix tersebut akan diawali dengan  /admin untuk url nya dan pemanggilan href akan diawali dengan (admin.) sesuai name
//prefix " digunakan ketika path akan digunakan berkali-kali (dibeberapa route) untuk mempersingkat penulisannya pake prefix

//tanpa prefix
//Route::get('/admin/dashboard' ....)-> name('admin/dashboard')
//Route::
Route::middleware('isAdmin')->prefix('/admin')->name('admin.')->group(function () {
    Route::get('/tickets/chart', [TicketController::class, 'chartData'])->name('tickets.chart');
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    //cinema
    Route::prefix('/cinemas')->name('cinemas.')->group(function () {
        Route::get('/', [CinemaController::class, 'index'])->name('index');
        Route::get('/create', [CinemaController::class, 'create'])->name('create');
        Route::post('/store', [CinemaController::class, 'store'])->name('store');
        //{id} : parameter placeholder, digunakan untuk mengirim data ke controller
        //digunakan untuk spesifikasi data
        //{id} : id, karena bagian uniknya (pk) ada di id
        Route::get('/edit/{id}', [CinemaController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [CinemaController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [CinemaController::class, 'destroy'])->name('delete');
        Route::get('/export', [CinemaController::class, 'exportExcel'])->name('export');
        Route::get('/trash', [CinemaController::class, 'trash'])->name('trash');
        Route::patch('/restore/{id}', [CinemaController::class, 'restore'])->name('restore');
        Route::delete('/delete-permanent/{id}', [CinemaController::class, 'deletePermanent'])->name('delete_permanent');

        Route::get('/datatables', [CinemaController::class, 'dataForDatatables'])->name('datatables');
    });

    // pengguna
    Route::prefix('/users')->name('users.')->group(function () {
        Route::get('/index', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/store', [UserController::class, 'storeAdmin'])->name('store');
        //parameter placeholder -> {id} : mencari data spesifik
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('edit');
        //karena edit perlu tau data spesifik, pake id karna data unik nya ada di id
        Route::put('/update/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [UserController::class, 'destroy'])->name('delete');
        Route::get('/export', [UserController::class, 'exportExcel'])->name('export');
        Route::get('/trash', [UserController::class, 'trash'])->name('trash');
        Route::patch('/restore/{id}', [UserController::class, 'restore'])->name('restore');
        Route::delete('/delete-permanent/{id}', [UserController::class, 'deletePermanent'])->name('delete_permanent');

        Route::get('/datatables', [UserController::class, 'dataForDatatables'])->name('datatables');
    });

    //film
    Route::prefix('/movies')->name('movies.')->group(function () {
        Route::get('/chart', [MovieController::class, 'dataChart'])->name('chart');
        Route::get('/', [MovieController::class, 'index'])->name('index');
        Route::get('/create', [MovieController::class, 'create'])->name('create');
        Route::post('/store', [MovieController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [MovieController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [MovieController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [MovieController::class, 'destroy'])->name('delete');
        Route::put('/nonaktif/{id}', [MovieController::class, 'nonAktif'])->name('nonaktif');
        Route::get('/export', [MovieController::class, 'exportExcel'])->name('export');

        // recycle bin
        //munculkan data sampah
        Route::get('/trash', [MovieController::class, 'trash'])->name('trash');
        //mengubah jadi dikemballikan ke belum terhapus (bukan sampah)
        Route::patch('/restore/{id}', [MovieController::class, 'restore'])->name('restore');
        //mengapus dari DB (database)
        Route::delete('/delete-permanent/{id}', [MovieController::class, 'deletePermanent'])->name('delete_permanent');

        Route::get('/datatables', [MovieController::class, 'dataForDatatables'])->name('datatables');
    });
});

// akses petugas
Route::middleware(isStaff::class)->prefix('staff')->name('staff.')->group(function () {
    Route::prefix('promos')->name('promos.')->group(function () {
        Route::get('/', [PromoController::class, 'index'])->name('index');
        Route::get('/create', [PromoController::class, 'create'])->name('create');
        Route::post('/store', [PromoController::class, 'store'])->name('store');
        Route::get('/{promo}/edit', [PromoController::class, 'edit'])->name('edit');
        Route::put('/{promo}', [PromoController::class, 'update'])->name('update');
        Route::delete('/{promo}', [PromoController::class, 'destroy'])->name('delete');
        Route::get('/export', [PromoController::class, 'exportExcel'])->name('export');

        // recycle bin
        //munculkan data sampah
        Route::get('/trash', [PromoController::class, 'trash'])->name('trash');
        //mengubah jadi dikemballikan ke belum terhapus (bukan sampah)
        Route::patch('/restore/{id}', [PromoController::class, 'restore'])->name('restore');
        //mengapus dari DB (database)
        Route::delete('/delete-permanent/{id}', [PromoController::class, 'deletePermanent'])->name('delete_permanent');

        Route::get('/datatables', [PromoController::class, 'dataForDatatables'])->name('datatables');
    });

    //jadwal tayang
    Route::prefix('/schedules')->name('schedules.')->group(function () {
        Route::get('/', [ScheduleController::class, 'index'])->name('index');
        Route::post('/store', [ScheduleController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [ScheduleController::class, 'edit'])->name('edit');
        //PUT : memungkinkan perubahan semua data,  PATCH : perubahan hanya pada beberapa data
        Route::patch('/update/{id}', [ScheduleController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [ScheduleController::class, 'destroy'])->name('delete');
        Route::get('/export', [ScheduleController::class, 'exportExcel'])->name('export');

        // recycle bin
        //munculkan data sampah
        Route::get('/trash', [ScheduleController::class, 'trash'])->name('trash');
        //mengubah jadi dikemballikan ke belum terhapus (bukan sampah)
        Route::patch('/restore/{id}', [ScheduleController::class, 'restore'])->name('restore');
        //mengapus dari DB (database)
        Route::delete('/delete-permanent/{id}', [ScheduleController::class, 'deletePermanent'])->name('delete_permanent');

        Route::get('/datatables', [ScheduleController::class, 'dataForDatatables'])->name('datatables');

    });
});
