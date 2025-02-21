<?php
namespace Routes;

use Controllers\CartController;
use Lib\Router;

use Controllers\ProductController;
use Controllers\UserController;
use Controllers\ErrorController;
use Controllers\CategoryController;
use Controllers\OrderController;
use Models\Product;

class Routes{
    public static function index(){
        //CONTENIDO PRINCIPAL
        Router::add('GET','/',function(){
            (new ProductController())->index();
        });

        //USUARIO
        //Verify Email
        Router::add('GET', '/User/verifyEmail', function() {
            (new UserController())->VerifyEmail();
        });
        
        //Login
        Router::add('GET','/login',function(){
            (new UserController())->Login();
        });
        Router::add('POST','/login',function(){
            (new UserController())->Login();
        });
        //Login
        Router::add('GET','/register',function(){
            (new UserController())->Register();
        });
        Router::add('POST','/register',function(){
            (new UserController())->Register();
        });
        Router::add('GET', '/logout', function() {
            (new UserController())->Logout();
        });
        
        //CATEGORIAS
        Router::add('GET','/HandleCategory',function(){
            (new CategoryController())->HandleCategory();
        });
        Router::add('GET','/CreateCategory',function(){
            (new CategoryController())->CreateCategory();
        });
        Router::add('POST','/CreateCategory',function(){
            (new CategoryController())->CreateCategory();
        });
        
        Router::add('GET','/DeleteCategory/:id',function($id){
            (new CategoryController())->DeleteCategory($id);
        });
        Router::add('POST','/DeleteCategory/:id',function($id){
            (new CategoryController())->DeleteCategory($id);
        });
        
        Router::add('GET', '/EditCategory', function() {
            (new CategoryController())->EditCategory();
        });
        Router::add('POST', '/EditCategory', function() {
            (new CategoryController())->EditCategory();
        });

        //PRODUCTOS
        Router::add('GET','/HandleProducts', function() {
            (new ProductController())->HandleProducts();
        });
        Router::add('POST','/HandleProducts', function() {
            (new ProductController())->HandleProducts();
        }); 
        
        Router::add('GET','/CreateProduct', function() {
            (new ProductController())->CreateProduct();
        });
        Router::add('POST','/CreateProduct', function() {
            (new ProductController())->CreateProduct();
        });

        Router::add('GET','/DeleteProduct/:id', function($id) {
            (new ProductController())->DeleteProduct($id);
        });
        Router::add('POST','/DeleteProduct/:id', function($id) {
            (new ProductController())->DeleteProduct($id);
        });

        Router::add('GET','/EditProduct', function() {
            (new ProductController())->EditProduct();
        });
        Router::add('POST','/EditProduct', function() {
            (new ProductController())->EditProduct();
        });

        Router::add('GET','/EditImage', function() {
            (new ProductController())->EditImage();
        });
        Router::add('POST','/EditImage', function() {
            (new ProductController())->EditImage();
        });

        Router::add('GET', '/Catalog', function() {
            (new ProductController())->SeeCatalog();
        });
        Router::add('POST', '/Catalog', function() {
            (new ProductController())->SeeCatalog();
        });

        //CARRITO
        Router::add('GET','/Cart',function(){
            (new CartController())->showCart();
        });

        Router::add('GET','/addProductCart/:id', function($id) {
            (new CartController())->addProductCart($id);
        });
        Router::add('POST','/addProductCart/:id', function($id) {
            (new CartController())->addProductCart($id);
        });

        Router::add('GET','/DeleteProductCart/:id', function($id) {
            (new CartController())->DeleteProductCart($id);
        });
        Router::add('GET','/quitProductCart/:id', function($id) {
            (new CartController())->quitProductCart($id);
        });

        Router::add('POST','/ClearCart', function() {
            (new CartController())->ClearCart();
        });

        Router::add('GET','/ClearCart', function() {
            (new CartController())->ClearCart();
        });

        Router::add('POST','/ClearCartProductSuccessful', function() {
            (new CartController())->ClearCartProductSuccessful();
        });

        Router::add('GET','/ClearCartProductSuccessful', function() {
            (new CartController())->ClearCartProductSuccessful();
        });
        
        //PEDIDOS
        Router::add('GET','/order',function(){
            (new OrderController())->Order();
        });
        Router::add('POST','/order',function(){
            (new OrderController())->Order();
        });
        Router::add('GET','/HandleOrder',function(){
            (new OrderController())->HandleOrder();
        });
        Router::add('POST','/HandleOrder',function(){
            (new OrderController())->HandleOrder();
        });
        Router::add('GET','/UpdateStatusOrder',function(){
            (new OrderController())->UpdateStatusOrder();
        });
        Router::add('POST','/UpdateStatusOrder',function(){
            (new OrderController())->UpdateStatusOrder();
        });
        Router::add('GET','SeeMyOrders/:id',function($id){
            (new OrderController())->SeeMyOrders($id);
        });
        Router::add('POST','SeeMyOrders/:id',function($id){
            (new OrderController())->SeeMyOrders($id);
        });

        //ERROR 404
        Router::add('GET','/not-found',function(){
            ErrorController::error404();
        });
        

        Router::dispatch();
    }
}