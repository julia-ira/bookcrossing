import { Component } from 'angular2/core';
import { HTTP_PROVIDERS } from 'angular2/http';
import { RouteConfig, ROUTER_DIRECTIVES, ROUTER_PROVIDERS } from 'angular2/router';
import { BooklistComponent } from './booklist/booklist.component';
import { BookDetailComponent } from './booklist/bookdetail.component';

@Component({
    selector: 'my-app',
    template: `<a [routerLink]="['Booklist']">До списку книг!</a><router-outlet></router-outlet>`,
    directives: [ROUTER_DIRECTIVES],
    providers: [HTTP_PROVIDERS, ROUTER_PROVIDERS]
})

@RouteConfig([
    /*{ path: "/", redirectTo: ["Booklist"] },*/
    { path: '/booklist/...', as: 'Booklist', component: BooklistComponent }
    /*{ path: '/book/:id', as: 'Bookdetail', component: BookDetailComponent }*/
])

export class AppComponent {}
