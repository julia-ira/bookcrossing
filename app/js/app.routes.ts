import { provideRouter, RouterConfig } from '@angular/router';
import { BooklistComponent } from './booklist/booklist.component';
import { bookRoutes } from './booklist/books.routes';
import { AuthenticationController } from './authentication/authentication.controller';
import { LoginComponent} from './authentication/login.component';

const routes: RouterConfig = [
    ...bookRoutes,
    { path: 'booklist', component: BooklistComponent, canActivate: [AuthenticationController] },
    { path: 'login', component: LoginComponent }
];

export const appRouterProviders = [
    provideRouter(routes)
];
