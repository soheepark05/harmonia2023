<?php

use Dajangter\Route;

Route::get("/", "MainController@index");
Route::post("/", "MainController@myVillage");

Route::get("/search", "SearchController@index");

Route::get("/login", "LoginController@index");
Route::post("/login", "LoginController@loginPS");

Route::get("/logout", "LoginController@logout");

Route::get("/register", "LoginController@register");
Route::post("/register", "LoginController@registerPS");

Route::get("/sell", "SellController@index");
Route::post("/sell/process", "SellController@process");

Route::get("/location", "LocationController@index");
Route::post("/location", "LocationController@setLocation");

Route::get("/shop", "ShopController@index");
Route::post("/shop/shopNameChange", "ShopController@shopNameChange");
Route::post("/shop/shopIntroduceChange", "ShopController@shopIntroduceChange");
Route::post("/shop/shopProfileChange", "ShopController@shopProfileChange");
Route::post("/shop/qnaAdd", "ShopController@qnaAdd");
Route::post("/shop/loadQna", "ShopController@loadQna");

Route::get("/view", "ViewController@index");
Route::post("/view/delete", "ViewController@deletePS");
Route::post("/view/steam", "ViewController@steam");

Route::get("/chat", "ChatController@index");