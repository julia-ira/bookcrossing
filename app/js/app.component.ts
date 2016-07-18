import { Component } from '@angular/core';
import { HTTP_PROVIDERS } from '@angular/http';
import { ROUTER_DIRECTIVES, provideRouter, RouterConfig } from '@angular/router';
import { BooklistComponent } from './booklist/booklist.component';
import { BookDetailComponent } from './booklist/bookdetail.component';

@Component({
    selector: 'my-app',
    template: `<a [routerLink]="['/books']">До списку книг!</a><router-outlet></router-outlet>`,
    directives: [ROUTER_DIRECTIVES],
    providers: [HTTP_PROVIDERS]
})

export class AppComponent {}