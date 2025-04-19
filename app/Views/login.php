<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="grid gap-6 lg:grid-cols-2 lg:gap-12 max-w-6xl mx-auto">
    <div class="flex flex-col justify-center space-y-4">
        <div class="space-y-2">
            <h1 class="text-3xl font-bold tracking-tighter sm:text-5xl">Bienvenido de nuevo</h1>
            <p class="text-gray-500 md:text-xl/relaxed lg:text-base/relaxed xl:text-xl/relaxed">Inicia sesión en tu cuenta para acceder a todas las funciones de nuestra plataforma.</p>
        </div>
        <div class="flex flex-col gap-2 min-[400px]:flex-row">
            <a href="#" class="inline-flex h-10 items-center justify-center rounded-md bg-gray-900 px-8 text-sm font-medium text-gray-50 shadow transition-colors hover:bg-gray-900/90 focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-gray-950">Saber más</a>
            <a href="#" class="inline-flex h-10 items-center justify-center rounded-md border border-gray-200 bg-white px-8 text-sm font-medium shadow-sm transition-colors hover:bg-gray-100 hover:text-gray-900 focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-gray-950">Registrarse</a>
        </div>
    </div>
    <div class="mx-auto w-full max-w-md space-y-6">
        <div class="rounded-lg border shadow-sm" data-v0-t="card">
            <div class="flex flex-col p-6 space-y-1">
                <h3 class="tracking-tight text-2xl font-bold">Iniciar Sesión</h3>
                <p class="text-sm">Ingresa tus credenciales para acceder a tu cuenta</p>
            </div>
            <div class="p-6 pt-0 space-y-4">
                <div id="form_login"></div>
                <div id="submit"></div>
            </div>
            <div class="flex items-center p-6 pt-0">
                <p class="text-sm text-gray-500">¿No tienes una cuenta? <a href="<?= base_url('register') ?>" class="text-blue-600 hover:underline">Regístrate</a></p>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
    jQuery(document).ready(($) => {
        $('#form_login').dxForm({
            formData: {
                username: '',
                password: '',
            },
            items: [{
                    dataField: 'username',
                    editorOptions: {
                        placeholder: 'johndoe',
                        valueChangeEvent: 'keyup',
                        showClearButton: true,
                        elementAttr: {
                            id: 'username',
                        },
                    },
                    label: {
                        template: (data) => {
                            return $('<div>').addClass('flex gap-x-1 items-center').append(
                                $('<i>').addClass('dx-icon-user text-sm'),
                                $('<span>').addClass('text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70').attr('for', 'username').text('Nombre de usuario')
                            );
                        }
                    },
                    validationRules: [{
                        type: 'required',
                        message: 'El nombre de usuario es requerido'
                    }]
                },
                {
                    dataField: 'password',
                    editorOptions: {
                        placeholder: '••••••••',
                        mode: 'password',
                        valueChangeEvent: 'keyup',
                        elementAttr: {
                            id: 'password',
                        },
                        showClearButton: true,
                        buttons: ['clear',
                            {
                                location: 'after',
                                name: 'password',
                                widget: 'dxButton',
                                options: {
                                    icon: 'eyeopen',
                                    type: 'text',
                                    onClick: function(e) {
                                        const field = $('#password').dxTextBox('instance');
                                        if (field.option('mode') === 'password') {
                                            field.option('mode', 'text');
                                            e.component.option('icon', 'eyeclose');
                                        } else {
                                            field.option('mode', 'password');
                                            e.component.option('icon', 'eyeopen');
                                        }
                                    }
                                }
                            },
                        ]
                    },
                    label: {
                        template: (data) => {
                            return $('<div>').addClass('flex gap-x-1 items-center').append(
                                $('<i>').addClass('dx-icon-lock text-sm'),
                                $('<span>').addClass('text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70').attr('for', 'password').text('Contraseña')
                            );
                        }
                    },
                    validationRules: [{
                        type: 'required',
                        message: 'La contraseña es requerida'
                    }]
                },
                {
                    itemType: 'simple',
                    editorType: 'dxCheckBox',
                    dataField: 'remember_me',
                    label: {
                        visible: false
                    },
                    editorOptions: {
                        text: 'Recuerdame',
                        value: false,
                    },
                },
            ]
        });

        $('#submit').dxButton({
            text: 'Iniciar sesión',
            width: '100%',
            type: 'default',
            useSubmitBehavior: true
        });
    })
</script>
<?= $this->endSection() ?>