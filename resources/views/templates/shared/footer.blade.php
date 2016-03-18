<footer>
    <li>
        <a href="http://jennyswiftcreations.com/privacy-policy">Privacy Policy</a>
    </li>

    <li>
        <a href="http://jennyswiftcreations.com/credits">Credits</a>
    </li>
</footer>

@include('templates.shared.feedback-component')
@include('templates.shared.loading-component')
@include('templates.shared.navbar-component')
@include('pages.items.items-page-component')
@include('pages.items.item-popup-component')
@include('pages.items.alarms-component')
@include('pages.items.urgent-items-component')
@include('pages.items.trash-page-component')
@include('pages.items.item.item-component')
@include('pages.categories.components.categories-component')

<script src="//js.pusher.com/3.0/pusher.min.js"></script>
<script type="text/javascript" src="{{ elixir("js/all.js") }}"></script>