<script>
   module.exports = {
    data: function() {
        return {
            who: 'world---------------------------------'
        }
    }
}
</script>


<template>   <!-- topbar -->
        <div class="topbar bg-off-white-two">
            <div class="container-lg">
                <div class="topbar-inner">
                    <div class="topbar-item">
                        <div class="topbar-item-list">
                            <div class="topbar-list-thumb">
                                <i class="flaticon-chat"></i>
                            </div>
                            <div class="topbar-list-content">
                                <a href="#">Chat With A Consultant</a>
                            </div>
                        </div>
                        <div class="topbar-item-list">
                            <div class="topbar-list-thumb">
                                <i class="flaticon-phone"></i>
                            </div>
                            <div class="topbar-list-content">
                                <a href="tel:678-215-7765">+678-215-7765</a>
                            </div>
                        </div>
                    </div>
                    <div class="topbar-item">
                        <div class="topbar-item-list">
                            <div class="topbar-list-thumb">
                                <i class="flaticon-chat"></i>
                            </div>
                            <div class="topbar-list-content">
                                <a href="mailto:info@blim.com">helpdesk@servermango.com</a>
                            </div>
                        </div>
                        <div class="topbar-item-list navbar-language">
                            <button class="language-option" type="button" id="language1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="lang-name"></span>
                                <span class="language-arrow"><i class='bx bx-chevron-down'></i></span>
                            </button>
                            <div class="dropdown-menu language-top-menu" aria-labelledby="language1">
                                <a class="dropdown-item" href="#">
                                    <img src="assets/images/uk.png" alt="flag" class="dropdown-flag-icon">
                                    English
                                </a>
                                <a class="dropdown-item" href="#">
                                    <img src="assets/images/germany.png" alt="flag" class="dropdown-flag-icon">
                                    Deutsch
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</template>

