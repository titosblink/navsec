<script>
(function () {
    const form    = document.getElementById('filter-form');
    const input   = document.getElementById('search-input');
    const clearBtn= document.getElementById('clear-search');
    const spinner = document.getElementById('search-spinner');
    if (!form || !input) return;

    let timer;

    function submit() {
        if (spinner) spinner.classList.remove('hidden');
        form.submit();
    }

    // Debounced live search on text input
    input.addEventListener('input', function () {
        clearBtn && clearBtn.classList.toggle('hidden', !this.value);
        clearTimeout(timer);
        timer = setTimeout(submit, 380);
    });

    // Clear button
    if (clearBtn) {
        clearBtn.addEventListener('click', function () {
            input.value = '';
            this.classList.add('hidden');
            submit();
        });
    }

    // Instant submit on any select change
    form.querySelectorAll('select').forEach(sel => {
        sel.addEventListener('change', submit);
    });
})();
</script>
