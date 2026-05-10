<div x-data="{
        show: false,
        text: '',
        xCoordinate: 0,
        yCoordinate: 0,

        display(event) {
            console.log('hi');
            this.show = true;
            this.text = event.detail.note;
            this.xCoordinate = event.detail.x;
            this.yCoordinate = event.detail.y;
        },

        hide() {
            this.show = false;
        }

    }"

    @pdf-show-tooltip.window="display($event)"
    @pdf-hide-tooltip.window="hide()"

    id="note-tooltip" x-show="show" x-transition.opacity.duration.100ms
    x-bind:style="`top: ${yCoordinate + 5}px; left: ${xCoordinate + 5}px;`" x-text="text"
    class="fixed z-50 py-2 px-4 text-sm font-medium text-white bg-zinc-900 dark:bg-zinc-700 rounded-lg shadow-lg pointer-events-auto max-w-md max-h-48 overflow-y-auto overflow-x-hidden whitespace-pre-wrap wrap-break-word"
    style="display: none;">
</div>
