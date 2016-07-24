import { bootstrap } from '@angular/platform-browser-dynamic';
import { HTTP_PROVIDERS } from '@angular/http';
import { AUTH_PROVIDERS } from 'angular2-jwt';
import { AuthenticationController } from './authentication/authentication.controller';
import { AppComponent } from './app.component';
import { appRouterProviders } from './app.routes';

bootstrap(AppComponent, [
        appRouterProviders,
        HTTP_PROVIDERS,
        AUTH_PROVIDERS,
        AuthenticationController
    ])
    .catch(err => console.error(err));