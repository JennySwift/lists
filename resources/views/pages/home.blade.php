<!DOCTYPE html>
<html lang="en">
<head>
    @include('templates.shared.head')
    @include('templates.shared.links')
</head>
<body>

<div id="app">
    <f7-statusbar></f7-statusbar>

    <f7-panel left reveal>
        <f7-block>
            <f7-list contacts-list>
                <f7-list-group>
                    <f7-list-item class="panel-close" link="/items" title="Items"></f7-list-item>
                    <f7-list-item class="panel-close" link="/categories" title="Categories"></f7-list-item>
                    <f7-list-item class="panel-close" link="/trash" title="Trash"></f7-list-item>
                </f7-list-group>
            </f7-list>
        </f7-block>

        <f7-block>
            <f7-list contacts-list>
                <f7-list-group>
                    <f7-list-item class="panel-close" external link="https://jennyswiftcreations.com/privacy-policy" title="Privacy"></f7-list-item>
                    <f7-list-item class="panel-close" external link="https://jennyswiftcreations.com/credits" title="Credits"></f7-list-item>
                    <f7-list-item class="panel-close" external link="https://jennyswiftcreations.com" title="jennyswiftcreations"></f7-list-item>
                </f7-list-group>
            </f7-list>
        </f7-block>

    </f7-panel>



    <item-popup></item-popup>
    <new-item></new-item>
    <items-filter></items-filter>
    <selector></selector>

    <f7-view id="main-view" main>

    </f7-view>

</div>

@include('templates.shared.scripts')

</body>
</html>