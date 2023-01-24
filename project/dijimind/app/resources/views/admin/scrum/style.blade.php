<style>
    .item-detail .scrumboard-item-options {
        display:none;
    }
    .scrumboard-item {
        overflow:hidden;
    }
    @media screen and (min-width:1000px) {
        .scrumboard {
            padding: 24px 16px 1px;
        }
        .scrumboard .scrumboard-col {
            -ms-flex: 0 0 auto;
            flex: 0 0 auto;
            width: 410px;
            margin-right: 12px;
        }
    }
    .scrumboard .scrumboard-item {
        color: #000;
    }
    .scrumboard-item {
        position: relative;
        min-height: 42px;
        padding: 10px 87px 10px 10px;
        margin-bottom: 15px;
        font-weight: 600;
        color: #000;
        background-color: #fcf7e6;
        border-bottom: 1pxsolidrgba(168,126,0,.1);
        box-shadow: 0 5px 8pxrgba(168,126,0,.05);
        border-radius: 10px;
    }
    .scrumboard-item-content {
        font-weight: 400 !important;
    }
    .progress {
        height: 60px;
    }
    .cursor-pointer {
        cursor:move !important;
    }
</style>