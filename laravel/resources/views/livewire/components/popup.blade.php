<div style="position: absolute;">
    @persist('popup-display')
        <style>
            .popup
            {
                padding: 10px;
                min-height: 20px;
                max-width: 370px !important;
                min-width: 180px;
                width: max-content;
                border-radius: 4px;
                font-family: var(--font);
                font-size: 15px;
                transition: 2s;
                text-align: center;
                vertical-align: middle;
                font-weight: 500;
                transition: 1.5s;
                z-index: 5000;
                display: flex;
                align-items: center;
                justify-content: center;
                top: 10px;
                left: 50%;
                transform: translateX(-50%);
                border: solid 1px #e7e7e7;
            }

            .popup-display
            {
                top: 15px;
                left: 50%;
                transform: translateX(-50%);
                min-width: 160px;
                overflow: hidden;
                display: grid;
                gap: 4px;
            }
            .light-popup
            {
                background: #262626;
                color: white;
            }
        </style>

        <div class="popup-display" id="popup-display"></div>

        <script>
            document.addEventListener('livewire:navigated', () => {
                add_events();
            });

            document.addEventListener('livewire:init', () => {
                add_events();
            });

            function add_events()
            {
                if(!window.saved_popup)
                window.addEventListener('saved-popup', event => {
                    popup_display = document.getElementById('popup-display');

                    var saved_popup = document.createElement('div');
                    saved_popup.className = 'popup shadow-sm light-popup';
                    saved_popup.textContent = event['detail'][0];
                    saved_popup.setAttribute("popover", "");
                    
                    popup_display.appendChild(saved_popup);
                    saved_popup.showPopover();

                    setTimeout(() => {
                        saved_popup.style.opacity = "0";
                    }, event['detail'][1] ? event['detail'][1] : 1000);

                    setTimeout(() => {
                        saved_popup.style.height = "0";
                        saved_popup.style.padding = "0";
                        saved_popup.style.margin = "0";
                    }, event['detail'][1] ? event['detail'][1] + 1500 : 2500);

                    setTimeout(() => {
                        popup_display.removeChild(saved_popup);
                    }, event['detail'][1] ? event['detail'][1] + 3100 : 4100);
                });
                window.saved_popup = true;

                if(!window.updated_popup)
                window.addEventListener('updated-popup', event => {
                    popup_display = document.getElementById('popup-display');

                    var updated_popup = document.createElement('div');
                    updated_popup.className = 'popup shadow-sm light-popup';
                    updated_popup.textContent = event['detail'][0];
                    updated_popup.setAttribute("popover", "");
                    
                    popup_display.appendChild(updated_popup);
                    updated_popup.showPopover();

                    setTimeout(() => {
                        updated_popup.style.opacity = "0";
                    }, event['detail'][1] ? event['detail'][1] : 1000);

                    setTimeout(() => {
                        updated_popup.style.height = "0px";
                        updated_popup.style.padding = "0px";
                        updated_popup.style.margin = "0px";
                    }, event['detail'][1] ? event['detail'][1] + 1500: 2500);

                    setTimeout(() => {
                        popup_display.removeChild(updated_popup);
                    }, event['detail'][1] ? event['detail'][1] + 3100: 4100);
                });
                window.updated_popup = true;

                if(!window.deleted_popup)
                window.addEventListener('deleted-popup', event => {
                    popup_display = document.getElementById('popup-display');
                    var deleted_popup = document.createElement('div');
                    deleted_popup.className = 'popup shadow-sm light-popup';
                    deleted_popup.textContent = event['detail'][0];
                    deleted_popup.setAttribute("popover", "");

                    popup_display.appendChild(deleted_popup);
                    deleted_popup.showPopover();

                    setTimeout(() => {
                        deleted_popup.style.opacity = "0";
                    }, event['detail'][1] ? event['detail'][1] : 1000);

                    setTimeout(() => {
                        deleted_popup.style.height = "0";
                        deleted_popup.style.padding = "0";
                        deleted_popup.style.margin = "0";
                    }, event['detail'][1] ? event['detail'][1] + 1500 : 2500);

                    setTimeout(() => {
                        popup_display.removeChild(deleted_popup);
                    }, event['detail'][1] ? event['detail'][1] + 3100 : 4100);
                });
                window.deleted_popup = true;

                if(!window.alert_popup)
                window.addEventListener('alert-popup', event => {
                    Alert(event['detail'][0]);
                });
                window.alert_popup = true;

                if(!window.confirm_alert)
                window.addEventListener('confirm-alert', event => {
                    ConfirmAlert(event['detail'][0]);
                });
                window.confirm_alert = true;
            }
        </script>
    @endpersist
</div>
