import { Component } from '@angular/core';
import { Router, ROUTER_DIRECTIVES } from '@angular/router';
import { Location } from '@angular/common';
import { CORE_DIRECTIVES, FORM_DIRECTIVES } from '@angular/common';
import { Http, Headers } from '@angular/http';
import { AuthenticationService } from './authentication.service';

@Component({
    selector: 'login',
    directives: [ROUTER_DIRECTIVES, CORE_DIRECTIVES, FORM_DIRECTIVES],
    providers: [AuthenticationService],
    templateUrl: 'app/js/authentication/login.component.html'
})

export class LoginComponent {

    constructor(public router: Router, public http: Http, private authenticationService: AuthenticationService, private _location: Location) {}

    login(event: any, email: any, password: any) {
        event.preventDefault();
        this.authenticationService.login(email, password)
            .subscribe(
                response => {
                    console.log(response);
                    localStorage.setItem('id_token', response.json().id_token);
                    this._location.back();
                },
                error => {
                    console.log(error.text());
                }
            );
    }

}
