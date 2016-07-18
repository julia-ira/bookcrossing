import { provideRouter, RouterConfig } from '@angular/router';
import { BooklistComponent } from './booklist/booklist.component';

const routes: RouterConfig = [
    { path: 'booklist', component: BooklistComponent }
];

export const appRouterProviders = [
    provideRouter(routes)
];
