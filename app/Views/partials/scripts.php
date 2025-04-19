<!-- jQuery -->
<script src="<?= assets('js/jquery.js') ?>"></script>
<!-- DevExpress -->
<script src="<?= assets('js/dx.all.js') ?>?v=1.0"></script>
<!-- Notify -->
<script src="<?= assets('js/notify.js') ?>"></script>
<!-- Custom Scripts -->
<?= $this->renderSection('scripts') ?>
<script>
    const query = (url, method = 'GET', data = null, files = false) => {
        const base_url = '<?= base_url_api() ?>';

        const options = {
            url: base_url + url,
            type: method,
            data: data,
            headers: {
                Authorization: 'Bearer ' + localStorage.getItem('token_netcheck')
            }
        };

        if (files) options.processData = false;

        return $.ajax(options);
    }

    jQuery(document).ready(($) => {
        const notificaciones = <?= json_encode(session()->getFlashdata('notification')) ?>;
        if (notificaciones) {
            $.notify({
                message: notificaciones.message,
                type: notificaciones.type
            });
        }
    })
</script>