import { Component } from 'angular2/core';
import { HTTP_PROVIDERS } from 'angular2/http';
import { RouteConfig, ROUTER_DIRECTIVES, ROUTER_PROVIDERS } from 'angular2/router';

import { BooklistComponent } from './booklist/booklist.component';

@Component({
    selector: 'my-app',
    template: `<a [routerLink]="['Booklist']">Book list</a><router-outlet></router-outlet>`,
    directives: [ROUTER_DIRECTIVES],
    providers: [HTTP_PROVIDERS, ROUTER_PROVIDERS]
})

@RouteConfig([
    { path: '/booklist', as: 'Booklist', component: BooklistComponent }
])

export class AppComponent { }
