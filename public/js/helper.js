$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        }
    });
});

function ErrorAlert(title, msg) {
    Swal.fire(title, msg, 'error');
}

function SuccessAlert(title, msg) {
    Swal.fire(title, msg, 'success');
}

function AlertConfirm(title = 'Apakah Anda Yakin?', text = 'Apa anda yakin melanjutkan proses', fn) {
    Swal.fire({
        title: title,
        text: text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya'
    }).then((result) => {
        if (result.value) {
            fn();
        }
    });
}

async function AjaxPost(url, param = {}, onSuccess = function () {
}, onAccepted = function () {

}) {
    try {
        let response = await $.post(url, param);
        if (response['status'] === 200) {
            onSuccess();
        } else {
            onAccepted();
        }
    } catch (e) {
        ErrorAlert('Error', e.responseText.toString());
    }
}


function createLoader(text = 'sedang mengunduh data...', height = 600) {
    return '<div class="d-flex flex-column align-items-center justify-content-center" style="height: ' + height + 'px">' +
        '<div class="spinner-border" role="status" style="color: var(--bg-primary);">\n' +
        '  <span class="sr-only" style="color: #117d17;"></span>\n' +
        '</div>' +
        '<div style="color: var(--dark); font-weight: 500;">' + text + '</div>' +
        '</div>';
}

function blockLoading(state) {
    if (state) {
        $('#overlay-loading').css('display', 'flex')
    } else {
        $('#overlay-loading').css('display', 'none')
    }

}

function calculate_days(tgl1, tgl2) {
    let vTgl1 = new Date(tgl1);
    let vTgl2 = new Date(tgl2)
    let diff_in_time = vTgl2.getTime() - vTgl1.getTime();
    return diff_in_time / (1000 * 3600 * 24);
}

function DataTableGenerator(element, url = '/', col = [], colDef = [], data = function () {
}, extConfig = {}) {
    let baseConfig = {
        scrollX: true,
        processing: true,
        ajax: {
            type: 'GET',
            url: url,
            'data': data
        },
        columnDefs: colDef,
        columns: col,
        paging: true,
    };
    let config = {...baseConfig, ...extConfig};
    return $(element).DataTable(config);
}

function formatUang(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

function validateMessage(message, target = []) {
    $.each(target, function (k, v) {
        let elTarget = $('#' + v + '-error');
        if (!elTarget.hasClass('d-none')) {
            elTarget.addClass('d-none');
        }
    });

    for (const [key, value] of Object.entries(message)) {
        let elTarget = $('#' + key + '-error');
        elTarget.removeClass('d-none');
        elTarget.html(value[0]);
    }
}

function debounce(fn, delay) {
    var timer = null;
    return function () {
        var context = this,
            args = arguments;
        clearTimeout(timer);
        timer = setTimeout(function () {
            fn.apply(context, args);
        }, delay);
    };
}

function createEmptyProduct() {
    return '<div class="d-flex flex-column align-items-center justify-content-center" style="height: ' + 400 + 'px">' +
        '<div style="color: var(--dark); font-weight: 500;">Product Tidak Ditemukan...</div>' +
        '</div>';
}
