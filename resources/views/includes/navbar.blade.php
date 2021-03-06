<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 25-9-17
 * Time: 21:05
 *
 */

?>
<!-- The drawer is always open in large screens. The header is always shown,
  even in small screens. -->
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-drawer
            mdl-layout--fixed-header">
    <header class="mdl-layout__header">
        <div class="mdl-layout__header-row">
            <div class="mdl-layout-spacer"></div>
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--expandable
                  mdl-textfield--floating-label mdl-textfield--align-right">
                <label class="mdl-button mdl-js-button mdl-button--icon"
                       for="fixed-header-drawer-exp">
                    <i class="material-icons">search</i>
                </label>
                <div class="mdl-textfield__expandable-holder">
                    <input class="mdl-textfield__input" type="text" name="sample"
                           id="fixed-header-drawer-exp">
                </div>
            </div>
        </div>
    </header>
    <div class="mdl-layout__drawer">
        <span class="mdl-layout-title">My Health</span>
        <nav class="mdl-navigation">
            <a class="mdl-navigation__link" href="/profile"><i class="material-icons">rowing</i>  My profile</a>
            <a class="mdl-navigation__link" href="/bills"><i class="material-icons">account_balance_wallet</i>  My bills</a>
            <a class="mdl-navigation__link" href="/measurements"><i class="material-icons">insert_chart</i>  My measurements</a>
            <a class="mdl-navigation__link" href="/auth/logout/"><i class="material-icons">exit_to_app</i>  Logout</a>
        </nav>
    </div>
    <main class="mdl-layout__content">
        <div class="page-content">@yield('content')</div>
    </main>
</div>