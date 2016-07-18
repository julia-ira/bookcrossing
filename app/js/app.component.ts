import { Component } from '@angular/core';
import { HTTP_PROVIDERS } from '@angular/http';
import { ROUTER_DIRECTIVES } from '@angular/router';

@Component({
    selector: 'my-app',
    template: `<a [routerLink]="['booklist']">Book list</a><router-outlet></router-outlet>`,
    directives: [ROUTER_DIRECTIVES],
    providers: [HTTP_PROVIDERS]
})

export class AppComponent {}
