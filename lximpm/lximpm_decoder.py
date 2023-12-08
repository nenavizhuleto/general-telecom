
SYSTEM_STATES = [
    "Unknown",
    "Enable_LPS_OFF",
    "LOAD_DISCONNECT",
    "Enable_Load+Disconnect",
    "Wakeup",
    "On_Line",
    "On_Battery"
]

IM_FAILURES_1 = [
    "BypassDriverSenseFault",
    "BypassVoltageFault",
    "BypassRlyVSwitchFault",
    "LPONDriveSenseFault",
    "UPSCONDriveSenseFault",
    "IsoPwr_Fault",
    "External_ADC_Fault",
    "InternalVoltagesFault",
    "ACFuse_Fault",
    "Calibration_Fault",
    "SlavePicFault",
    "PwrModCtrl_Hwd_Fault",
    "SlaveSaysMasterBad_Fault",
    "I2CToPicFailure",
    "I2CToEEpromFailure",
    "MIMRIMHandoff"
]

IM_FAILURES_2 = [
    "Turbo_interrupt_failure",
    "EEPROM_Shadown_RAM_1",
    "EEPROM_Shadown_RAM_2",
    "BadInputSwFuseFault",
    "BypassContactSenseFault",
    "Ext_ADC_Noise_Fault",
    "Turbo_Noise_fault",
    "Im_Wont_Turn_Off",
    "Spare8",
    "Spare9",
    "Spare10",
    "Spare11",
    "Spare12",
    "Spare13",
    "Spare14",
    "FakeFaultForDebug"
]

SYS_FAILURES_1 = [
    "BypassRelayOpenFault",
    "BypassVShortFault",
    "RelayBounceFault",
    "BYPS_ContactsDriveFAult",
    "Im_Input_Compare_Fault",
    "FanVShortFault",
    "Fan1Fault",
    "Fan2Fault",
    "PwrModWillNotTurnOffFault",
    "Batt_Pack_Fault",
    "BypassStuckOnMains",
    "BypassStuckOnInverter",
    "Spare12_",
    "I2CNonIsoBusFailure",
    "I2cIsoBusFailure",
    "MIMRIMSystem",

]

SYS_FAILURES_2 = [
    "Analog_Fid_Bit_Fault",
    "Spare1",
    "Spare2",
    "Spare3",
    "Spare4",
    "Spare5",
    "Spare6",
    "Spare7",
    "Spare8",
    "Spare9",
    "Spare10",
    "Spare11",
    "Spare12",
    "Spare13",
    "Spare14",
    "Spare15",

]

OTHER_IM_FAILURES = [
    "I2CXAFailureOthyer",
    "I2CPICFailureOther",
    "I2CEEPROMFialureOther",
    "PwrModCtrol_Hwd_Fault",
    "SlaveSaysMasterBad_Fault",
    "EEPROM_Fault",
    "Spare6",
    "Spare7",
    "Spare8",
    "Spare9",
    "Spare10",
    "Spare11",
    "Spare12",
    "Spare13",
    "Spare14",
    "Spare15",

]

IM_INPUT_1 = [
    "Mod_Pres_0",
    "Mod_Pres_1",
    "Mod_Pres_2",
    "Mod_Pres_3",
    "Mod_Pres_4",
    "Mod_Pres_5",
    "Mod_Pres_6",
    "Mod_Pres_7",
    "Enable_IM",
    "MP_IN",
    "MOD_OK",
    "IMO_1",
    "CB_SNS",
    "MBYP",
    "OFFSW",
    "ONSW",

]

IM_INPUT_2 = [
    "BYPS",
    "BYPS2",
    "MOD_STAT_CK_NOT",
    "OTHER_UPS_ON",
    "OTHER_UPS_OFF",
    "OTHER_LPON",
    "OTHER_BYP_RLY",
    "MIM_TS_NOT",
    "BIT_8",
    "BIT_9",
    "BIT_10",
    "BIT_11",
    "BIT_12",
    "BIT_13",
    "BIT_14",
    "NotSetFlag",

]

DIAGNOSTIC_R1 = [
    "MST_LOBAT_RG1",
    "MST_SEL_COMM_RG1",
    "MST_IM_OK_RG1",
    "MST_LPON_TG1",
    "ARM_RG1",
    "BYP_CMD_RG1",
    "UPS_CON_RG1",
    "I2C_ADDRESS_RG1",
    "MST_LPOFF_RG1",
    "I2C_EXT_DISC_RG1",
    "I2C_MODDISC_RG1",
    "EEPROM_WP_RG1",
    "ISO_PWR_OK_RG1",
    "SYS_FAULT_RG1",
    "MST_MOD_BAD_LED_RG1",
    "SLAVE_CHECK_OK_RG1",
    "MIM_OUT_INTENT_RG1",
    "MOD_STAT_INTENT_RG1",
    "BATT_PRES_0_RG1",
    "BATT_PRES_1_RG1",
    "BATT_PRES_2_RG1",
    "BATT_PRES_3_RG1",
    "BATT_PRES_4_RG1",
    "BATT_PRES_5_RG1",
    "BATT_PRES_6_RG1",
    "BATT_PRES_7_RG1",
    "Spare_BIT26",
    "Spare_BIT27",
    "Spare_BIT28",
    "Spare_BIT29",
    "Spare_BIT30",
    "Spare_BIT31",

]

DIAGNOSTIC_R2 = [
    "UPS_CON_RG3",
    "UPS_ON_D_RG3",
    "UPS_OFF_D_RG3",
    "BYP_RLY_D_RG3",
    "BYP_CMD_RG3",
    "UPS_ON_OTHER_RG3",
    "UPS_OFF_OTHER_rg3",
    "BTP_RLY_OTHER_RG3",
    "LKD2AC1_RG3",
    "LKD2MODV_RG3",
    "FREQ_DEFAULT_50HZ_RG3",
    "GREATER_THAN_200V_RG3",
    "SINE_REF_OK_RG3",
    "MAIN_REDUNDANCY_FLAG_RG3",
    "PRIM_CHECK_STATE_RG3",
    "SLA_MOD_STAT_RG3",
    "MASTER_OK-RG3",
    "ARM_RG3",
    "MST_IM_OK_PIN_RG3",
    "MIM_REQ_OUT_LOW_RG3",
    "OTHER_IM_NOT_IN_RG3",
    "OTHER_IM_NOT_OK_RG3",
    "BATT_MOD_FAULT_RG3",
    "BATT_COMM_MOD_0_RG3",
    "BATT_COMM_MOD_1_RG3",
    "BATT_COMM_MOD_2_RG3",
    "BATT_COMM_MOD_3_RG3",
    "BATT_COMM_MOD_4_RG3",
    "BATT_COMM_MOD_5_RG3",
    "BATT_COMM_MOD_6_RG3",
    "BATT_COMM_MOD_7_RG3",
    "SINE_REF_CHANGE_MASK_RG3",

]

MODULE_CONTROL = [
    "Reset Faults",
    "DoSelfTest",
    "ModuleOn",
    "ForcedBattery",
    "ChargerOff",
    "InverterOff",
    "AllOff",
    "Bit7",
    "TotalShutDown",
    "ModuleConnect",
    "ForceFanHigh",
    "FlashLED",
    "OverChargerOn",
    "NewFaultAck",
    "ChargerOff",
    "Bit7",

]

MODULE_STATUS = [
    "PFCOnLine",
    "InverterOn",
    "ConnectSwitchOn",
    "Overload",
    "NewFault",
    "LastI2CxOK",
    "Bit6",
    "Bit7",
    "PFCOn",
    "UPSOnHi",
    "UPSOffHi",
    "ChargerCircuitOn",
    "ChargerEnabledHi",
    "OverChargeModeOn",
    "SoftStartRlyClosed",
    "FanHi",

]

MODULE_FAULT = [
    "DisconnectRelayFailedClosed",
    "CannotRunOnBatt",
    "CannotRunOnLine",
    "ChargerFault",
    "ErrorAmpFault",
    "FacctoryCalError",
    "OutputFuseFault",
    "Bit7",
    "OverloadShutdown",
    "ErrorAmpShutDown",
    "DisconnectRelayFailedOpen",
    "InverterFault",
    "FanFault",
    "OverTemp",
    "DCBusInternalOV",
    "LogicPowerFault",

]

MODULE_CONDITION = [
    ",SelfTestDone",
    ",SelfTestPassed",
    ",DCBusOK",
    ",BatteryDead",
    ",DCImbalancePlus",
    ",DCImbalanceMinus",
    ",ModuleSinglePhase",
    ",InputSinglePhase",
    ",Output2ph120Volt",
    ",Input2ph120Deg",
    ",Bit2",
    ",Bit3",
    ",DCBusNotOKForCharger",
    ",PMSaysFactoryInfoGood",
    ",Bit6",
    ",Bit7",
]

CALIBRATION_STATUS = [
    "RestoreFactInfo",
    "E2PromWriteError",
    "E2PromreadError",
    "ReadFactInfo",
    "ReadFactCal",
    "BeginVoltageCal",
    "WriteFactInfo",
    "WriteFactCal",

]

LINE_STATUS = [
    "Bi0",
    "LineOK",
    "BelowLimitStillCanRun",
    "OnlyOneCanBeOn",
    "OnlyInverterCanBeOn",
    "OnlyChargerCanBeOn",
    "UnderVoltage",
    "OverVoltage",

]

INTERNAL_FLAGS = [
    "ModuleGood",
    "GoodOnLineOnly",
    "GoodOnBattOnly",
    "ModulePresent",
    "ModuleNewlyEnabled",
    "ModuleNewlyDisabled",
    "IICLocalFault",
    "Bit7",

]

PM_VALUES = [
    "Input Volts",
    "Battery volts",
    "Inv A Watts",
    "Inv B Watts",
    "Inv A Iout",
    "Inv B Iout",
    "Temperature",
    "DC Bus Positive",
    "DC Bus Negative",
    "PFC Pos Amps",
    "PFC Neg Amps",

]


def to_hex(val):
    res = int(val, 16)
    # print("Val: {} \t\t Res: {}".format(val, res))
    return res

# status = 1001-5B67-F000-2007-0000-02-38-7C-0C-00-00-00-52-AF-AF-03-02


def decode_pm_status(status: str, forSYMP4KI: bool):

    inputVoltCoef = 3.86 if forSYMP4KI else 1.33
    batVoltCoef = 1.1
    invWattCoef = 18
    invLoutCoef = 2.03
    tempCoef = 0.37
    dcBusCoef = 2 if forSYMP4KI else 1.114
    pfcAmpsCoef = 0.2

    if len(status.replace('-', '').replace(' ', '').replace('\n', '')) != 44:
        print("Incorrect status-code. Exiting...")
        return {
            "message": "Incorrect status code"
        }

    result = {}

    if status.find("-") != -1:
        status_raw = status.split('-')

    if status.find(" ") != -1:
        status_raw = status.split(' ')

    else:
        status_raw = [
            status[0:4],
            status[4:8],
            status[8:12],
            status[12:16],
            status[16:20],
            status[20:22],
            status[22:24],
            status[24:26],
            status[26:28],
            status[28:30],
            status[30:32],
            status[32:34],
            status[34:36],
            status[36:38],
            status[38:40],
            status[40:42],
            status[42:44],
        ]

    tx = {
        "ModuleControl": to_hex(status_raw[0]),
        "ModuleStatus": to_hex(status_raw[1]),
        "ModuleFault": to_hex(status_raw[2]),
        "ModuleCondition": to_hex(status_raw[3]),
        "LineStatus": to_hex(status_raw[4]),
        "InternalFlags": to_hex(status_raw[5]),
        "ModuleValues": [to_hex(x) for x in status_raw[6:]]
    }

    result['ModuleControl'] = {}
    for i, con in enumerate(MODULE_CONTROL):
        result["ModuleControl"][con] = 0
        if tx["ModuleControl"] >> i & 1:
            result["ModuleControl"][con] = 1

    result['ModuleStatus'] = {}
    for i, status in enumerate(MODULE_STATUS):
        result["ModuleStatus"][status] = 0
        if tx["ModuleStatus"] >> i & 1:
            result["ModuleStatus"][status] = 1

    result['ModuleFault'] = {}
    for i, fault in enumerate(MODULE_FAULT):
        result["ModuleFault"][fault] = 0
        if tx["ModuleFault"] >> i & 1:
            result["ModuleFault"][fault] = 1

    result['LineStatus'] = {}
    for i, status in enumerate(LINE_STATUS):
        result["LineStatus"][status] = 0
        if tx["LineStatus"] >> i & 1:
            result["LineStatus"][status] = 1

    result['InternalFlags'] = {}
    for i, flag in enumerate(INTERNAL_FLAGS):
        result["InternalFlags"][flag] = 0
        if tx["InternalFlags"] >> i & 1:
            result["InternalFlags"][flag] = 1

    result['ModuleValues'] = {}
    for i, val in enumerate(PM_VALUES):
        coef = 1
        if val.find("Input") != -1:
            coef = inputVoltCoef
        if val.find("Battery") != -1:
            coef = batVoltCoef
        if val.find("Watts") != -1:
            coef = invWattCoef
        if val.find("Iout") != -1:
            coef = invLoutCoef
        if val.find("Temp") != -1:
            coef = tempCoef
        if val.find("DC") != -1:
            coef = dcBusCoef
        if val.find("PFC") != -1:
            coef = pfcAmpsCoef

        result["ModuleValues"][val] = int(tx["ModuleValues"][i]) * coef

    return result

# status = "M-05-0001-000F-0100-1000-1010-0011-0101-10000001-10101010"


def decode_mim5_status(status: str):

    if len(status.replace('-', '').replace(' ', '').replace('\n', '')) != 47:
        print("Incorrect status-code. Exiting...")
        return {
            "message": "Incorrect status code"
        }

    result = {}

    if status.find("-") != -1:
        status_raw = status.split('-')

    if status.find(" ") != -1:
        status_raw = status.split(' ')

    else:
        status_raw = [
            status[0],
            status[1:3],
            status[3:7],
            status[7:11],
            status[11:15],
            status[15:19],
            status[19:23],
            status[23:27],
            status[27:31],
            status[31:39],
            status[39:]
        ]

    tx = {
        "MIM": status_raw[0],
        "system_state": to_hex(status_raw[1]),
        "IM_failures": [
            to_hex(status_raw[2]), to_hex(status_raw[3]),
        ],
        "SYS_failures": [
            to_hex(status_raw[4]), to_hex(status_raw[5])
        ],
        "other_IM_failures": to_hex(status_raw[6]),
        "IM_input": [
            to_hex(status_raw[7]), to_hex(status_raw[8])
        ],
        "diagnosticR": [
            to_hex(status_raw[9]), to_hex(status_raw[10])
        ]
    }

    result['SystemState'] = SYSTEM_STATES[tx["system_state"]]

    result['IM_Failures'] = {}
    for i, failure in enumerate(IM_FAILURES_1):
        result["IM_Failures"][failure] = 0
        if tx["IM_failures"][0] >> i & 1:
            result["IM_Failures"][failure] = 1

    for i, failure in enumerate(IM_FAILURES_2):
        result["IM_Failures"][failure] = 0
        if tx["IM_failures"][1] >> i & 1:
            result["IM_Failures"][failure] = 1

    result['SYS_Failures'] = {}
    for i, failure in enumerate(SYS_FAILURES_1):
        result["SYS_Failures"][failure] = 0
        if tx["SYS_failures"][0] >> i & 1:
            result["SYS_Failures"][failure] = 1

    for i, failure in enumerate(SYS_FAILURES_2):
        result["SYS_Failures"][failure] = 0
        if tx["SYS_failures"][1] >> i & 1:
            result["SYS_Failures"][failure] = 1

    result['Other_IM_Failures'] = {}
    for i, failure in enumerate(OTHER_IM_FAILURES):
        result["Other_IM_Failures"][failure] = 0
        if tx["other_IM_failures"] >> i & 1:
            result["Other_IM_Failures"][failure] = 1

    result['IM_Inputs'] = {}
    for i, input in enumerate(IM_INPUT_1):
        result["IM_Inputs"][input] = 0
        if tx["IM_input"][0] >> i & 1:
            result["IM_Inputs"][input] = 1

    for i, input in enumerate(IM_INPUT_2):
        result["IM_Inputs"][input] = 0
        if tx["IM_input"][1] >> i & 1:
            result["IM_Inputs"][input] = 1

    result['Diagnostics'] = {}
    for i, diagnostic in enumerate(DIAGNOSTIC_R1):
        result["Diagnostics"][diagnostic] = 0
        if tx["diagnosticR"][0] >> i & 1:
            result["Diagnostics"][diagnostic] = 1

    for i, diagnostic in enumerate(DIAGNOSTIC_R2):
        result["Diagnostics"][diagnostic] = 0
        if tx["diagnosticR"][1] >> i & 1:
            result["Diagnostics"][diagnostic] = 1

    return result

# decode_mim5_status("M057003000F010010001010001101011000000110101010")

# decode_pm_status(
#     "1001-5B67-F000-2007-0000-00-01-01-01-01-01-01-01-01-01-01-01", True)


if __name__ == "__main__":
    res = decode_pm_status(
        "0804 42A0 0002 2000 0002 08 47 7B 09 00 00 00 50 2C 91 00 00", False)
    print(res)
