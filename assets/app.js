console.log('Webpack encore')
// any CSS you import will output into a single css file (app.css in this case)
import 'bootstrap/dist/css/bootstrap.min.css';
import './styles/app.css';
import './fonts/fonts.css';
import './styles/style.scss';

// start the Stimulus application
import './bootstrap';
// You can specify which plugins you need
import { Tooltip, Toast, Popover } from 'bootstrap';

require('bootstrap-icons/font/bootstrap-icons.css');

import jquery from 'jquery';
const $ = require('jquery');
global.$ = global.jQuery = $;
