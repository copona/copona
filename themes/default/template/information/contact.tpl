<?php echo $header; ?>
<?php include(__DIR__ . '/../common/_page_open.tpl'); ?>
      <div class="cp-page-intro">
        <h1><?php echo $heading_title; ?></h1>
        <?php if ($description) { ?>
          <div class="cp-prose cp-muted"><?php echo $description; ?></div>
        <?php } ?>
      </div>
      <div class="cp-contact">
        <div class="cp-contact__info">
          <section class="cp-panel">
            <h2 class="cp-info__title"><?php echo $text_location; ?></h2>
            <?php $loc = ['name' => $store, 'image' => $image, 'address' => $address, 'geocode' => $geocode, 'telephone' => $telephone, 'fax' => $fax, 'open' => $open, 'comment' => $comment]; include(__DIR__ . '/_location.tpl'); ?>
          </section>
          <?php if ($locations) { ?>
            <section class="cp-panel">
              <h2 class="cp-info__title"><?php echo $text_store; ?></h2>
              <?php foreach ($locations as $loc) { ?>
                <details class="cp-disclosure">
                  <summary><?php echo $loc['name']; ?></summary>
                  <?php include(__DIR__ . '/_location.tpl'); ?>
                </details>
              <?php } ?>
            </section>
          <?php } ?>
        </div>
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="cp-form cp-contact__form">
          <fieldset class="cp-fieldset">
            <legend><?php echo $text_contact; ?></legend>
            <?php $field = ['name' => 'name', 'label' => $entry_firstname, 'value' => $name, 'id' => 'input-name', 'error' => $error_name, 'required' => true, 'autocomplete' => 'name']; include(__DIR__ . '/../common/_field.tpl'); ?>
            <?php $field = ['name' => 'email', 'label' => $entry_email, 'value' => $email, 'id' => 'input-email', 'type' => 'email', 'error' => $error_email, 'required' => true, 'autocomplete' => 'email']; include(__DIR__ . '/../common/_field.tpl'); ?>
            <?php $field = ['name' => 'enquiry', 'label' => $entry_enquiry, 'value' => $enquiry, 'id' => 'input-enquiry', 'type' => 'textarea', 'error' => $error_enquiry, 'required' => true]; include(__DIR__ . '/../common/_field.tpl'); ?>
            <?php echo $captcha; ?>
          </fieldset>
          <div class="cp-actions">
            <button type="submit" class="btn btn-primary"><?php echo $button_submit; ?></button>
          </div>
        </form>
      </div>
<?php include(__DIR__ . '/../common/_page_close.tpl'); ?>
<?php echo $footer; ?>
