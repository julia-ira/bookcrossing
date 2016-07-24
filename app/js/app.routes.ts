import { provideRouter, RouterConfig } from '@angular/router';
import { BooklistComponent } from './booklist/booklist.component';
import { SecuredBooklistComponent } from './booklist/secured-booklist.component';
import { AuthenticationController } from './authentication/authentication.controller';
import { LoginComponent } from './authentication/login.component';

const routes: RouterConfig = [
    { path: 'booklist', component: BooklistComponent },
    // canActivate - checking if user is logged in to proceed with booklist component
    { path: 'secured', component: SecuredBooklistComponent, canActivate: [AuthenticationController] },
    { path: 'login', component: LoginComponent }
];

export const appRouterProviders = [
    provideRouter(routes)
];
