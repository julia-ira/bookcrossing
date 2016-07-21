import { Component } from '@angular/core';
import { Router, ROUTER_DIRECTIVES } from '@angular/router';
import { CORE_DIRECTIVES, FORM_DIRECTIVES } from '@angular/common';
import { Http, Headers } from '@angular/http';
import { contentHeaders } from './headers';
import { AuthenticationService } from './authentication.service';

@Component({
    selector: 'login',
    directives: [ROUTER_DIRECTIVES, CORE_DIRECTIVES, FORM_DIRECTIVES],
    providers: [AuthenticationService],
    templateUrl: 'app/js/authentication/login.component.html'
})

export class LoginComponent {

    constructor(public router: Router, public http: Http, private authenticationService: AuthenticationService) {}

    login(event: any, username: any, password: any) {
        event.preventDefault();

    }

    signup(event: any) {
        event.preventDefault();
        //this.router.navigate(['/signup']);
    }
}
