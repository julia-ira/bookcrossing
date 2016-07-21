import { bootstrap } from '@angular/platform-browser-dynamic';
import { AppComponent } from './app.component';
import { AUTH_PROVIDERS } from 'angular2-jwt';
import { AuthenticationController } from './authentication/authentication.controller';
import { appRouterProviders } from './app.routes';

bootstrap(AppComponent, [
        appRouterProviders,
        AUTH_PROVIDERS,
        AuthenticationController
    ])
    .catch(err => console.error(err));