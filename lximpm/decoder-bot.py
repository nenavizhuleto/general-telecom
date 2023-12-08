import json
import lximpm_decoder

from telegram.ext import (
    Application,
    CommandHandler,
    ContextTypes,
    ConversationHandler,
    MessageHandler,
    filters,
)
from telegram import ReplyKeyboardMarkup, ReplyKeyboardRemove, Update
from typing import Dict
import logging
TOKEN = "6201510561:AAEyHImND5QISNRDzy7xi8dR8M6mh4GebHs"
PM = "PM"
MIM5 = "MIM5"

# Enable logging
logging.basicConfig(
    format="%(asctime)s - %(name)s - %(levelname)s - %(message)s", level=logging.INFO
)
logger = logging.getLogger(__name__)

CHOOSING, TYPING_REPLY, TYPING_CHOICE = range(3)

reply_keyboard = [
    [PM, MIM5],
]
markup = ReplyKeyboardMarkup(reply_keyboard, one_time_keyboard=True)


async def start(update: Update, context: ContextTypes.DEFAULT_TYPE) -> int:
    """Start the conversation and ask user for input."""
    logger.info(
        f"Id: {update.message.from_user.id} "
        f"User: {update.message.from_user.full_name} "
        f"Chat: {update.message.chat_id} "
        "Started dialog with bot"
    )
    await update.message.reply_text(
        "Select Device",
        reply_markup=markup,
    )

    return CHOOSING


async def regular_choice(update: Update, context: ContextTypes.DEFAULT_TYPE) -> int:
    """Ask the user for info about the selected predefined choice."""
    text = update.message.text
    context.user_data["device"] = text
    logger.info(
        f"Id: {update.message.from_user.id} "
        f"User: {update.message.from_user.full_name} "
        f"Chat: {update.message.chat_id} "
        f"Selected {text} device "
    )
    await update.message.reply_text(f"You selected {text} device. Send status code to decode")

    return TYPING_REPLY


async def custom_choice(update: Update, context: ContextTypes.DEFAULT_TYPE) -> int:
    """Ask the user for a description of a custom category."""
    logger.info(
        f"Id: {update.message.from_user.id} "
        f"User: {update.message.from_user.full_name} "
        f"Chat: {update.message.chat_id} "
        f"Tried to select unknown {update.message.text} device "
    )
    await update.message.reply_text(
        'Unknown device, please select correct.',
        reply_markup=markup
    )

    return CHOOSING


async def received_information(update: Update, context: ContextTypes.DEFAULT_TYPE) -> int:
    """Store info provided by user and ask for the next category."""
    user_data = context.user_data
    text = update.message.text
    device = user_data["device"]
    user_data[device] = text
    del user_data["device"]

    logger.info(
        f"Id: {update.message.from_user.id} "
        f"User: {update.message.from_user.full_name} "
        f"Chat: {update.message.chat_id} "
        f"Provided {text} status code "
    )

    result = ""
    if device == PM:
        result = lximpm_decoder.decode_pm_status(text, False)
        await update.message.reply_text(text=json.dumps(result, indent=4, separators=(",", ":")), reply_markup=markup)

    if device == MIM5:
        result = lximpm_decoder.decode_mim5_status(text)
        text = json.dumps(result, indent=4, separators=(",", ":"))

        msg_half_len = int(len(text) / 2)
        if msg_half_len > 500:
            await update.message.reply_text(text=text[:msg_half_len], reply_markup=markup)
            await update.message.reply_text(text=text[msg_half_len:], reply_markup=markup)
        else:
            await update.message.reply_text(text=text, reply_markup=markup)

    logger.info(
        f"Id: {update.message.from_user.id} "
        f"User: {update.message.from_user.full_name} "
        f"Chat: {update.message.chat_id} "
        f"Decoded {result} "
    )

    return CHOOSING


async def done(update: Update, context: ContextTypes.DEFAULT_TYPE) -> int:
    """Display the gathered info and end the conversation."""
    return CHOOSING


def main() -> None:
    """Run the bot."""
    # Create the Application and pass it your bot's token.
    application = Application.builder().token(TOKEN).build()

    # Add conversation handler with the states CHOOSING, TYPING_CHOICE and TYPING_REPLY
    conv_handler = ConversationHandler(
        entry_points=[CommandHandler("start", start)],
        states={
            CHOOSING: [
                MessageHandler(
                    filters.Regex(
                        "^(PM|MIM5)$"), regular_choice
                ),
                MessageHandler(filters.TEXT, custom_choice),
            ],
            TYPING_CHOICE: [
                MessageHandler(
                    filters.TEXT & ~(filters.COMMAND | filters.Regex(
                        "^Done$")), regular_choice
                )
            ],
            TYPING_REPLY: [
                MessageHandler(
                    filters.TEXT & ~(filters.COMMAND |
                                     filters.Regex("^Done$")),
                    received_information,
                )
            ],
        },
        fallbacks=[MessageHandler(filters.TEXT, done)],
    )

    application.add_handler(conv_handler)

    # Run the bot until the user presses Ctrl-C
    application.run_polling()


if __name__ == "__main__":
    main()
