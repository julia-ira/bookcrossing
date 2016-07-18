import { provideRouter, RouterConfig } from '@angular/router';
import { BooklistComponent } from './booklist/booklist.component';
import { bookRoutes }        from './booklist/books.routes';

const routes: RouterConfig = [
...bookRoutes,
  { path: 'booklist', component: BooklistComponent }
];

export const appRouterProviders = [
  provideRouter(routes)
];